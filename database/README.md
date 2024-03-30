# Database Structure

```mermaid
erDiagram
    organizations ||..o{ organizations_relatives : stakeholders
    organizations {
        unsignedBigInt id PK
        uuid uuid UK
        varchar(150) name
        varchar(50) alias
        varchar email UK
        varchar(20) phone
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    organizations_relatives ||--|| organizations : stakeholder
    organizations_relatives ||--|| people : stakeholder
    organizations_relatives {
        unsignedBigInt id PK
        unsignedBigInt organization_id FK
        morph stakeholder
        varchar(100) code UK
        boolean is_primary
        unsignedSmallInt type
        unsignedSmallInt status
        unsignedSmallInt personnel_status
        date start_date
        date finish_date
    }

    people {
        unsignedBigInt id PK
        uuid uuid UK
        unsignedBigInt user_id FK
        varchar(150) name
        varchar(20) prefix
        varchar(20) suffix
        varchar(50) alias
        char(16) nik UK
        varchar email UK
        varchar(20) phone
        date birth_date
        char(4) birth_place_code
        char(1) gender
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    people ||..o{ people_relatives : relatives
    people_relatives ||--|| people : relative
    people_relatives {
        unsignedBigInt person_id FK
        unsignedBigInt relative_id FK
        unsignedSmallInt status
    }

    addresses }o..|| organizations : addresses
    addresses }o..|| people : addresses
    addresses {
        unsignedBigInt id PK
        morph addressable
        unsignedSmallInt type
        varchar line
        char(3) rt
        char(3) rw
        char(10) village_code
        char(6) district_code
        char(4) regency_code
        char(2) province_code
        char(5) postal_code
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    users ||..|| people : credential
    users {
        unsignedBigInt id PK
        varchar(150) name
        varchar email
        varchar password
    }

    files ||--o{ files : revisions
    files {
        uuid id PK
        unsignedBigInt revision_id FK
        varchar title
        varchar name
        varchar path
        varchar disk
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    organizations }o..|| file_attached : files
    people }o..|| file_attached : files
    file_attached ||--|| files : attachments
    file_attached {
        unsignedBigInt file_upload_id FK
        morph attachable
        unsignedSmallInt type
    }
```
---
## Entities

Either a companies or an individuals are tent to shares some similarities, which is they must have a way for externals to communicate with them. The most common ways are by `email` or `phone`. In that regard, another similarity is they must have a name, but there's case that differenciate how we describe the way we call them. A organizations are commonly use term `legal_name` and `alias_name`, while an individuals are commonly using `full_name` and `nick_name`. Either of them serve the same purposes.

Despite those similarities they must have some differences, including :
- An individual does have gender, while a company doesn't
- An individual might have a credential so they can logging in to the system, while a company shouldn't

```mermaid
classDiagram
    Entity <|-- Organization
    Entity <|-- Person
    Person "1" ..> "1" User : credential

    class Entity {
        string name
        null|string alias
        string email
        null|string phone
        null|string summary
    }
    class Organization~Entity~ {
        int id
        uuid uuid
    }
    class Person~Entity~ {
        int id
        uuid uuid
        null|int user_id
        string gender
        credential() User
    }
    class User {
        int id
        string name
        string email
        profile() Person
    }
```

## Organizationes

A business must have some sort of [relationship](https://www.investopedia.com/terms/b/business-relations.asp) to the externals, either to another business or individuals regardless of its size. The most common term to describe it is [stakeholder](https://www.investopedia.com/terms/s/stakeholder.asp).

> A stakeholder is a person or group with an interest in an enterprise - [What Are Stakeholders: Definition, Types, and Examples](https://www.investopedia.com/terms/s/stakeholder.asp)

In this implementation we try to cover 5 most basic and common business relationship, which are :

- **Owner**

  A company or individual stackholder who own the business.

- **Subsidiary**

  A company that act as subsidiary or child business.

- **Employee**

  A individual that act as employee of the business. 

- **Customer**

  A company or individual that act as purchase goods from the business.

- **Supplier**

  A company or individual that provides raw materials for the business so they can produce their goods.

- **Vendor**

  A company or individual who provides pre-made or even ready-made goods for the business so they can proceed the raw materials into goods.

The term "stakeholders" itself is actually covers a lot more than that. But at this stage we can't afford to comply those types of relationship, simply because that's beyond of our cababilities to handle them at the moment.

```mermaid
classDiagram
     OrganizationRelative --> Organization : stakeholders
     OrganizationRelative --> Person : stakeholders
     Organization --> OrganizationRelative : businessRelatives

    class Organization~Entity~ {
        int id
        owners() Entity[]
        subsidiaries() Entity[]
        customers() Entity[]
        suppliers() Entity[]
        vendors() Entity[]
    }
    class Person~Entity~ {
        int id
    }
    class OrganizationRelative {
        int id
        boolean is_internal
        string code
        int type
        int status
        stakeholder() Entity
    }
```

### `organizations`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `uuid` | `uuid` | `unique` | |
| `name` | `varchar(150)` | | Must contain legal name of the business, e.g : `PT. Creasi Tekno Solusi` |
| `alias` | `varchar(50)`, `nullable` | | Must contain alias name from its legal name of the business, e.g : `Creasico` |
| `email` | `varchar`, `nullable` | `unique` | The business primary email address, e.g : `hello@creasi.co` |
| `phone` | `varchar(20)`, `nullable` | | The business primary phone number |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

### `organizations_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `organization_id` | `unsignedBigInt` | `foreign` | |
| `stakeholder` | `morphs`, `nullable` | | |
| `code` | `varchar(100)`, `nullable` | `unique` | Unique identifier of business relationship |
| `type` | `unsignedSmallInt`, `nullable` | | |
| `status` | `unsignedSmallInt`, `nullable` | | |
| `start_date` | `date`, `nullable` | | |
| `finish_date` | `date`, `nullable` | | |

**Relation Properties**
- `organization_id` : reference `organizations`

## Employment

Essentially the `employments` mechanism can be done using `organizations_relatives`, but since it has certain entities that differs compared to the other stakeholders we should pivot it into different table. Another reason is it could be easier to manage the spesific relation when using dedicated table.

```mermaid
classDiagram
     Employments --> Person : employments
     Organization --> Employments : employments

    class Organization {
        int id
        employees() Person[]
    }
    class Person {
        int id
        company() null|Organization
        employers() Organization[]
    }
    class Employments {
        int id
    }
```

### `employments` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `organization_id` | `unsignedBigInt` | `foreign` | |
| `stakeholder` | `morphs`, `nullable` | | |
| `is_primary` | `boolean` | | In some circumstance, a business might have multiple `business_relative` with the same `type` in this case they should be able to decide which one is mark as primary relative. default: `false` |
| `code` | `varchar(100)`, `nullable` | `unique` | Unique identifier of business relationship |
| `type` | `unsignedSmallInt`, `nullable` | | |
| `status` | `unsignedSmallInt`, `nullable` | | |
| `personnel_status` | `unsignedSmallInt`, `nullable` | | At this stage, this fields will only be available for `employment` relative, which is `business` to `personnel` that has `type` of `employee` |
| `start_date` | `date`, `nullable` | | |
| `finish_date` | `date`, `nullable` | | |

**Relation Properties**
- `employer_id` : reference `organizations`
- `employee_id` : reference `people`

**Employment Statuses**
- Unemployeed
- Fulltime
- Parttime
- Probation
- Internship
- Freelance

> **NOTE**
> 
> Field `type` and `status` shouldn't be detachable so we can maintain historical changes of the personnel in the company.

## Person and Profile

Every individuals should have their own identity, it also can helps a business to identify better of their individuals. But there's a circumstance that a business doesn't really care about that, all they need is just a way to communicate with the individuals, and that's it.

Meanwhile, a business might want to be able to also communicate with their personnel's relatieves. That case mostly used by a company to it employees in regards when there's an unexpected happens and the company decided to communicate it to their employee's relatives

```mermaid
classDiagram
    Person ..> PersonRelative : relative
    PersonRelative --> Person : personnel

    class Person {
        int id
        profile() null|Profile
    }
    class PersonRelative {
        int person_id
        int relative_id
        int status
    }
```

### `people`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `uuid` | `uuid` | `unique` | |
| `user_id` | `unsignedBigInt`, `nullable` | `foreign` | |
| `name` | `varchar(150)` | | |
| `prefix` | `varchar(10)`, `nullable` | | |
| `suffix` | `varchar(10)`, `nullable` | | |
| `alias` | `varchar(50)`, `nullable` | | |
| `nik` | `char(16)`, `nullable` | | |
| `email` | `varchar`, `nullable` | `unique` | |
| `phone` | `varchar(20)`, `nullable` | | |
| `birth_date` | `date`, `nullable` | | |
| `birth_place_code` | `char(4)`, `nullable` | `foreign` | |
| `gender` | `char(1)` | | |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `user_id` : reference `users`

### `people_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `person_id` | `unsignedBigInt` | `foreign` | |
| `relative_id` | `unsignedBigInt` | `foreign` | |
| `status` | `unsignedSmallInt`, `nullable` | | |

**Relation Properties**
- `person_id` : reference `people`
- `relative_id` : reference `people`

**Person Relative Statuses**
- Child
- Spouse
- Sibling
- SiblingsChild
- Parent
- ParentsSibling
- Grandparent
- Grandchild
- Cousin

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `birth_place_code` : reference `regencies`

**Person Educations**
- Uneducated
- SD
- SMP
- SMA
- D1
- D2
- D3
- S1
- S2
- S3

**Person Religions**
- Other
- Islam
- Christian
- Catholic
- Hinduism
- Buddhism
- Confucianism

## Addresses

Another similarity either individual and business is they have address, even in some cases they might has multiple addresses. For instance that an individual might have multiple addresses when they're live in different location than their identity (NIK). In that case a business might want to have that information where its live also the location from its identity (NIK).

It also possible that either business or individual put their address information for different purposes, e.g shipment address and billing address. Your mileage might vary.

```mermaid
classDiagram
    Organization "1" ..> "*" Address : addresses
    Person "1" ..> "*" Address : addresses

    class Address {
        int id
        addressable() Entity
    }
    class Organization~Entity~ {
        int id
        addresses() Address[]
    }
    class Person~Entity~ {
        int id
        addresses() Address[]
    }
```

### `addresses`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `addressable` | `morphs`, `nullable` | | |
| `type` | `unsignedSmallInt`, `nullable` | | |
| `line` | `varchar` | | |
| `rt` | `char(3)`, `nullable` | | |
| `rw` | `char(3)`, `nullable` | | |
| `village_code` | `char(10)`, `nullable` | `foreign` | |
| `district_code` | `char(6)`, `nullable` | `foreign` | |
| `regency_code` | `char(4)`, `nullable` | `foreign` | |
| `province_code` | `char(2)`, `nullable` | `foreign` | |
| `postal_code` | `char(5)`, `nullable` | | |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `village_code` : reference `villages`
- `district_code` : reference `districts`
- `regency_code` : reference `regencies`
- `province_code` : reference `provinces`

**Address Types**
Currently there's only 2 types of address, which is :
- `Legal` : Meaning that the address data is what defined in their legal document,
- `Resident` : Meaning that the address data  is actually the place where their live,

But it can be extended by the config `creasi.base.address.types` value, that said we have flexibility to define the address type per-project-basis.

## Uploaded Files

Last but not least, either company or individual might also be able to have certain files or documents to runs the business. This functionality can serve any kind of purposes and its might be vary depending of the business. For instance, as simple as both might want to upload their photo e.g an profile avatar or company logo. Meanwhile there's business that want to serve their invoice, quotation or any other document.

It also possible to store an `.xlsx` or `.csv` file that would be used for data imports, so the import process can be done in the background and once the process is finished, the system can remove the stored files and keep it neat and clean as the business goes.

```mermaid
classDiagram
    Organization "1" ..> "*" FileAttached : files
    Person "1" ..> "*" FileAttached : files
    File "1" ..> "*" File : revisions
    FileAttached <--> File : attachment

    class File {
        int id
        string revision_id
        string title
        string name
        string path
        string disk
        null|string summary
        revisions() File[]
    }
    class FileAttached {
        int id
        morph attachable
        int type
        attachable() Entity
    }
    class Organization~Entity~ {
        int id
        files() File[]
    }
    class Person~Entity~ {
        int id
        files() File[]
    }
```

### `files`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `uuid` | `primary` | |
| `revision_id` | `uuid`, `nullable` | `foreign` | Indicates that this row is actually a revision of parent `id` |
| `title` | `varchar`, `nullable` | | |
| `name` | `varchar` | | |
| `path` | `varchar`, `nullable` | | |
| `disk` | `varchar`, `nullable` | | |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `revision_id` : reference `files`

### `file_attached` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `file_upload_id` | `uuid` | `foreign` | |
| `attachable` | `morphs`, `nullable` | | |
| `type` | `insignedSmallInt` | | |

**Relation Properties**
- `file_upload_id` : reference `files`

**File Attached Types**
- Other
- Document
- Image
- Avatar
