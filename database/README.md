# Database Structure

```mermaid
erDiagram
    businesses ||..o{ business_relatives : stakeholders
    businesses {
        unsignedBigInt id PK
        varchar(150) name
        varchar(50) alias
        varchar email UK
        varchar(20) phone
        unsignedSmallInt tax_status
        varchar(16) tax_id
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    business_relatives ||--|| businesses : stakeholder
    business_relatives ||--|| personnels : stakeholder
    business_relatives {
        unsignedBigInt id PK
        unsignedBigInt business_id FK
        morph stakeholder
        varchar(100) code UK
        boolean is_primary
        unsignedSmallInt type
        unsignedSmallInt status
        unsignedSmallInt employment_status
        date start_date
        date finish_date
    }

    personnels {
        unsignedBigInt id PK
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
        unsignedSmallInt education
        unsignedSmallInt religion
        unsignedSmallInt tax_status
        varchar(16) tax_id
        varchar(200) summary
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    personnels ||..o{ personnel_relatives : relatives
    personnel_relatives ||--|| personnels : relative
    personnel_relatives {
        unsignedBigInt personnel_id FK
        unsignedBigInt relative_id FK
        unsignedSmallInt status
    }

    addresses }o..|| businesses : addresses
    addresses }o..|| personnels : addresses
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

    users ||..|| personnels : credential
    users {
        unsignedBigInt id PK
        varchar(150) name
        varchar email
        varchar password
    }

    file_uploads ||--o{ file_uploads : revisions
    file_uploads {
        unsignedBigInt id PK
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

    businesses }o..|| file_attached : files
    personnels }o..|| file_attached : files
    file_attached ||--|| file_uploads : attachments
    file_attached {
        unsignedBigInt file_upload_id FK
        morph attachable
        unsignedSmallInt type
    }
```
---
## Entities

Either a companies or an individuals are tent to shares some similarities, which is they must have a way for externals to communicate with them. The most common ways are by `email` or `phone`. In that regard, another similarity is they must have a name, but there's case that differenciate how we describe the way we call them. A businesses are commonly use term `legal_name` and `alias_name`, while an individuals are commonly using `full_name` and `nick_name`. Either of them serve the same purposes.

Despite those similarities they must have some differences, including :
- An individual does have gender, while a company doesn't
- An individual might have a credential so they can logging in to the system, while a company shouldn't

```mermaid
classDiagram
    Entity <|-- Business
    Entity <|-- Personnel
    Personnel "1" ..> "1" User : credential

    class Entity {
        string name
        null|string alias
        string email
        null|string phone
        null|string summary
    }
    class Business~Entity~ {
        int id
        null|int tax_status
        null|string tax_id
    }
    class Personnel~Entity~ {
        int id
        null|int user_id
        string gender
        credential() User
    }
    class User {
        int id
        string name
        string email
        string password
        identity(): Personnel
    }
```

## Businesses

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
     BusinessRelative --> Business : stakeholders
     BusinessRelative --> Personnel : stakeholders
     Business --> BusinessRelative : businessRelatives

    class Business~Entity~ {
        int id
        owners() Entity[]
        subsidiaries() Entity[]
        customers() Entity[]
        suppliers() Entity[]
        vendors() Entity[]
    }
    class Personnel~Entity~ {
        int id
    }
    class BusinessRelative {
        int id
        boolean is_internal
        string code
        int type
        stakeholder() Entity
    }
```

### `businesses`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `name` | `varchar(150)` | | Must contain legal name of the business, e.g : `PT. Creasi Tekno Solusi` |
| `alias` | `varchar(50)`, `nullable` | | Must contain alias name from its legal name of the business, e.g : `Creasico` |
| `email` | `varchar`, `nullable` | `unique` | The business primary email address, e.g : `hello@creasi.co` |
| `phone` | `varchar(20)`, `nullable` | | The business primary phone number |
| `tax_status` | `unsignedSmallInt`, `nullable` | | |
| `tax_id` | `varchar(16)`, `nullable` | | |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

### `business_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `business_id` | `unsignedBigInt` | `foreign` | |
| `stakeholder` | `morphs`, `nullable` | | |
| `code` | `varchar(100)`, `nullable` | `unique` | Unique identifier of business relationship |
| `type` | `unsignedSmallInt`, `nullable` | | |
| `status` | `unsignedSmallInt`, `nullable` | | |
| `start_date` | `date`, `nullable` | | |
| `finish_date` | `date`, `nullable` | | |

**Relation Properties**
- `business_id` : reference `businesses`

## Employment

Essentially the `employments` mechanism can be done using `business_relatives`, but since it has certain entities that differs compared to the other stakeholders we should pivot it into different table. Another reason is it could be easier to manage the spesific relation when using dedicated table.

```mermaid
classDiagram
     Employments --> Personnel : employments
     Business --> Employments : employments

    class Business {
        int id
        employees() Personnel[]
    }
    class Personnel {
        int id
        company() null|Business
        employers() Business[]
    }
    class Employments {
        int id
    }
```

### `employments` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
| `business_id` | `unsignedBigInt` | `foreign` | |
| `stakeholder` | `morphs`, `nullable` | | |
| `is_primary` | `boolean` | | In some circumstance, a business might have multiple `business_relative` with the same `type` in this case they should be able to decide which one is mark as primary relative. default: `false` |
| `code` | `varchar(100)`, `nullable` | `unique` | Unique identifier of business relationship |
| `type` | `unsignedSmallInt`, `nullable` | | |
| `status` | `unsignedSmallInt`, `nullable` | | |
| `employment_status` | `unsignedSmallInt`, `nullable` | | At this stage, this fields will only be available for `employment` relative, which is `business` to `personnel` that has `type` of `employee` |
| `start_date` | `date`, `nullable` | | |
| `finish_date` | `date`, `nullable` | | |

**Relation Properties**
- `employer_id` : reference `businesses`
- `employee_id` : reference `personnels`

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

## Personnel and Profile

Every individuals should have their own identity, it also can helps a business to identify better of their individuals. But there's a circumstance that a business doesn't really care about that, all they need is just a way to communicate with the individuals, and that's it.

Meanwhile, a business might want to be able to also communicate with their personnel's relatieves. That case mostly used by a company to it employees in regards when there's an unexpected happens and the company decided to communicate it to their employee's relatives

```mermaid
classDiagram
    Personnel ..> PersonnelRelative : relative
    PersonnelRelative --> Personnel : personnel

    class Personnel {
        int id
        profile() null|Profile
    }
    class PersonnelRelative {
        int personnel_id
        int relative_id
        int status
    }
```

### `personnels`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt`, `incrementing` | `primary` | |
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
| `education` | `varchar(3)`, `nullable` | | |
| `religion` | `unsignedTinyInt`, `nullable` | | |
| `tax_status` | `unsignedSmallInt`, `nullable` | | |
| `tax_id` | `varchar(16)`, `nullable` | | |
| `summary` | `varchar(200)`, `nullable` | | |

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `user_id` : reference `users`

### `personnel_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `personnel_id` | `unsignedBigInt` | `foreign` | |
| `relative_id` | `unsignedBigInt` | `foreign` | |
| `status` | `unsignedSmallInt`, `nullable` | | |

**Relation Properties**
- `personnel_id` : reference `personnels`
- `relative_id` : reference `personnels`

**Personnel Relative Statuses**
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

**Personnel Educations**
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

**Personnel Religions**
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
    Business "1" ..> "*" Address : addresses
    Personnel "1" ..> "*" Address : addresses

    class Address {
        int id
        addressable() Entity
    }
    class Business~Entity~ {
        int id
        addresses() Address[]
    }
    class Personnel~Entity~ {
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
    Business "1" ..> "*" FileAttached : files
    Personnel "1" ..> "*" FileAttached : files
    FileUpload "1" ..> "*" FileUpload : revisions
    FileAttached <--> FileUpload : attachment

    class FileUpload {
        int id
        string revision_id
        string title
        string name
        string path
        string disk
        null|string summary
        revisions() FileUpload[]
    }
    class FileAttached {
        int id
        morph attachable
        int type
        attachable() Entity
    }
    class Business~Entity~ {
        int id
        files() FileUpload[]
    }
    class Personnel~Entity~ {
        int id
        files() FileUpload[]
    }
```

### `file_uploads`

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
- `revision_id` : reference `file_uploads`

### `file_attached` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `file_upload_id` | `uuid` | `foreign` | |
| `attachable` | `morphs`, `nullable` | | |
| `type` | `insignedSmallInt` | | |

**Relation Properties**
- `file_upload_id` : reference `file_uploads`

**File Attached Types**
- Avatar
- Image
- Document
