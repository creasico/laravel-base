# Database Structure

```mermaid
erDiagram
    companies ||--o{ company_relatives : hasMany
    companies {
        unsignedBigInt id PK
        string code
        string name
        string email
        string phone_number
        string logo_path
        string summary
    }

    company_relatives ||--|| companies : stakeholder
    company_relatives ||--|| personnels : stakeholder
    company_relatives {
        unsignedBigInt id PK
        unsignedBigInt company_id FK
        morph stakeholder
        int type
        boolean is_internal
        string remark
    }

    companies ||--o{ employments : employees
    personnels ||--o{ employments : employer
    employments {
        unsignedBigInt company_id FK
        unsignedBigInt employee_id FK
        boolean is_primary
        int type
        int status
        date start_date
        date finish_date
        string remark
    }

    personnels {
        unsignedBigInt id PK
        string code
        string name
        string email
        string phone_number
        string logo_path
        string summary
    }
    
    personnels ||--o{ personnel_relatives : hasMany
    personnel_relatives ||--|| personnels : family
    personnel_relatives {
        unsignedBigInt personnel_id FK
        unsignedBigInt relative_id FK
        string status
        string remark
    }

    files ||--|| file_attached : hasMany
    files ||--o{ files : revisions
    files {
        unsignedBigInt id PK
        unsignedBigInt revision_id FK
        string title
        string name
        string path
        string drive
        string summary
    }
    file_attached }o..|| companies : uploadedFiles
    file_attached }o..|| personnels : uploadedFiles
    file_attached {
        unsignedBigInt file_id FK
        morph attached_to
    }

    addresses }o..|| companies : own
    addresses }o..|| personnels : own
    addresses {
        unsignedBigInt id PK
        morph owner
        boolean is_resident
        string line
        string rt
        string rw
        int village_code
        int district_code
        int regency_code
        int province_code
        int postal_code
        string summary
    }

    personnels ||..|| identities : morphOne
    identities {
        unsignedBigInt id PK
        morphs identity
        string nik
        string prefix
        string fullname
        string suffix
        string gender
        string birth_date
        string birth_place_code
        string education
        string religion
        string phone_number
        string photo_path
        string summary
    }
```
---
## Companies

```mermaid
classDiagram
    Company "1" <|--|> "*" Company : CompanyRelative
    Company "1" <|--|> "*" Personnel : CompanyRelative

    class Company {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    class Personnel {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
```

### `companies`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt` | `primary` | - |
| `code` | `string`, `nullable` | `unique` | - |
| `name` | `string` | | - |
| `email` | `string`, `nullable` | `unique` | - |
| `phone_number` | `varchar(20)`, `nullable` | | - |
| `logo_path` | `string`, `nullable` | | - |
| `summary` | `text`, `nullable` | | - |

**Model Attributes**
- `timestamps`
- `softDeletes`

### `company_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt` | `primary` | - |
| `company_id` | `unsignedBigInt` | `foreign` | - |
| `stakeholder` | `morphs`, `nullable` | | - |
| `type` | `unsignedSmallInt`, `nullable` | | - |
| `is_internal` | `boolean`, `default: false` | | - |
| `remark` | `text`, `nullable` | | - |

**Relation Properties**
- `company_id` : reference `companies`

## Employment

```mermaid
classDiagram
    Company "1" <|--|> "*" Personnel : Employment
    class Company {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    class Personnel {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
```

### `employments` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `company_id` | `unsignedBigInt` | `foreign` | - |
| `employee_id` | `unsignedBigInt` | `foreign` | - |
| `is_primary` | `boolean`, `default: false` | | - |
| `type` | `unsignedSmallInt`, `nullable` | | - |
| `status` | `unsignedSmallInt`, `nullable` | | - |
| `start_date` | `date`, `nullable` | | - |
| `finish_date` | `date`, `nullable` | | - |
| `remark` | `text`, `nullable` | | - |

**Relation Properties**
- `company_id` : reference `companies`
- `employee_id` : reference `personnels`

## Personnel and Identities

```mermaid
classDiagram
    Personnel "1" <|--|> "1" Identity : Profile
    Personnel "1" <|--|> "1" Personnel : PersonnelRelative
    class Personnel {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    class Identity {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
```

### `personnels`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt` | `primary` | - |
| `code` | `string`, `nullable` | `unique` | - |
| `name` | `string` | | - |
| `email` | `string`, `nullable` | `unique` | - |
| `phone_number` | `varchar(20)`, `nullable` | | - |
| `photo_path` | `string`, `nullable` | | - |
| `summary` | `text`, `nullable` | | - |

**Model Attributes**
- `timestamps`
- `softDeletes`

### `personnel_relatives` (morphPivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `personnel_id` | `unsignedBigInt` | `foreign` | - |
| `relative_id` | `unsignedBigInt` | `foreign` | - |
| `status` | `unsignedSmallInt`, `nullable` | | - |
| `remark` | `text`, `nullable` | | - |

**Relation Properties**
- `personnel_id` : reference `personnels`
- `relative_id` : reference `personnels`

### `identities`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt` | `primary` | - |
| `identity` | `morphs`, `nullable` | | - |
| `nik` | `char(16)`, `nullable` | | - |
| `prefix` | `varchar(10)`, `nullable` | | - |
| `fullname` | `string` | | - |
| `suffix` | `varchar(10)`, `nullable` | | - |
| `gender` | `char(1)` | | - |
| `birth_date` | `date`, `nullable` | | - |
| `birth_place_code` | `char(4)`, `nullable` | | - |
| `education` | `varchar(3)`, `nullable` | | - |
| `religion` | `unsignedTinyInt`, `nullable` | | - |
| `phone_number` | `varchar(20)`, `nullable` | | - |
| `photo_path` | `string`, `nullable` | | - |
| `summary` | `text`, `nullable` | | - |

**Model Attributes**
- `timestamps`
- `softDeletes`

## Addresses

```mermaid
classDiagram
    Company "*" <|--|> "1" Address : Own
    Personnel "*" <|--|> "1" Address : Own
    class Address {
        unsignedBigInt id
        morph owner
        timestamps() static
        softDeletes() static
    }
    class Company {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    class Personnel {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
```

### `addresses`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `unsignedBigInt` | `primary` | - |
| `owner` | `morphs`, `nullable` | | - |
| `is_resident` | `boolean` | | - |
| `line` | `string` | | - |
| `rt` | `char(3)`, `nullable` | | - |
| `rw` | `char(3)`, `nullable` | | - |
| `village_code` | `char(10)`, `nullable` | | - |
| `district_code` | `char(6)`, `nullable` | | - |
| `regency_code` | `char(4)`, `nullable` | | - |
| `province_code` | `char(2)`, `nullable` | | - |
| `postal_code` | `char(5)`, `nullable` | | - |
| `summary` | `text`, `nullable` | | - |

**Model Attributes**
- `timestamps`
- `softDeletes`

## Uploaded Files

```mermaid
classDiagram
    Company "*" <--> "1" FileAttached : AttachedTo
    Personnel "*" <--> "1" FileAttached : AttachedTo
    class File {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    FileAttached "1" --> "1" File : Attachment
    class FileAttached {
        unsignedBigInt id
        morph attached_to
    }
    class Company {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
    class Personnel {
        unsignedBigInt id
        timestamps() static
        softDeletes() static
    }
```

### `files`

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `id` | `uuid` | `primary` | - |
| `revision_id` | `uuid`, `nullable` | `foreign` | Indicates that this row is actually a revision of parent `id` |
| `title` | `string`, `nullable` | | - |
| `name` | `string` | | - |
| `path` | `string`, `nullable` | | - |
| `drive` | `string`, `nullable` | | - |
| `summary` | `string`, `nullable` | | - |

**Model Attributes**
- `timestamps`
- `softDeletes`

**Relation Properties**
- `revision_id` : reference `files`

### `file_attached` (pivot)

| Field | Attribute | Key | Description |
| --- | --- | :---: | --- |
| `file_id` | `uuid` | `foreign` | - |
| `attached_to` | `morphs`, `nullable` | | - |

**Relation Properties**
- `file_id` : reference `files`
