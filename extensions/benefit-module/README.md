# Benefit Module

## User Guide

For testing purposes a free gateway was implemented. Make sure to add `Free` gateway to sales funnel in `Admin->sales funnels` to pre-seeded
`sample` funnel.

After this process is finished, a `sample` subscription can be bought with the use of `Free` gateway. Each purchase adds 1 charge to user benefits.

Due to time-complexity, images of benefits have not been implemented.

### Enabling module

For this repo purposes, module is already loaded and set in config.

Enable the installed module in your `app/config/config.neon` file:

```neon
extensions:
	# ...
	- Crm\BenefitModule\DI\BenefitModuleExtension
```

### Generate ACL

```bash
php bin/command.php phinx:migrate # migrating the DB changes
php bin/command.php user:generate_access # generating user access resources
php bin/command.php api:generate_access # generating access for the API endpoints
php bin/command.php application:seed # seeding the required data to the DB
```

## API documentation

All examples use `http://crm.press` as a base domain. Please change the host to the one you use
before executing the examples.

All examples use `XXX` as a default value for authorization token, please replace it with the
real tokens:

* *User tokens.* Generated for each user during the login process, token identify single user when communicating between
  different parts of the system. The token can be read:
    * From `n_token` cookie if the user was logged in via CRM.
    * From the response of [`/api/v1/users/login` endpoint](https://github.com/remp2020/crm-users-module#post-apiv1userslogin) -
      you're free to store the response into your own cookie/local storage/session.

API responses can contain following HTTP codes:

| Value              | Description                                                                                                      |
|--------------------|------------------------------------------------------------------------------------------------------------------|
| 200 OK             | Successful response                                                                                              |
| 400 Bad Request    | Invalid request (missing required parameters or the request was matched, but with different value than expected) |
| 403 Forbidden      | Authorization fail (wrong user/API Bearer token), Restricted access                                              |
| 404 Not found      | Referenced resource wasn't found (for example token)                                                             |
| 500 Internal error |                                                                                                                  |

## Usage

#### POST `/api/v1/user-benefit/list`

Load list of user benefits

##### *Headers:*

| Name          | Value              | Required | Description |
|---------------|--------------------|----------|-------------|
| Content-Type  | `application/json` | yes      |             |
| Authorization | Bearer **String**  | yes      | User token  |

##### *Params:*

| Name    | Type    | Required | Description                         |
|---------|---------|----------|-------------------------------------|
| user_id | Array   | Yes      | ID of user for benefit list loading |

<details>
<summary>Example</summary>

```shell
curl -X POST \
  https://crm.press/api/v1/user-benefit/list \
  -H 'Authorization: Bearer XXX' \
  -H 'Content-Type: application/json' \
  -d '{"user_id":1}'
```

</details>

<details>
<summary>Response</summary>

```json5
{
  "status": "ok",
  "data": {
    "user_id": 1,
    "benefits": {
      "5": {
        "id": 5,
        "title": "a",
        "code": "b",
        "image": null,
        "valid_from": "2023-09-05T00:00:00+02:00",
        "valid_to": "2023-09-22T00:00:00+02:00"
      },
      "6": {
        "id": 6,
        "title": "b",
        "code": "sd",
        "image": null,
        "valid_from": "2023-09-03T00:00:00+02:00",
        "valid_to": "2023-09-20T00:00:00+02:00"
      }
    }
  }
}
```

</details>