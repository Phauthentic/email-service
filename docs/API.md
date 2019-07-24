# API

The API is a RESTful API that takes HTTP calls.

## Emails

### GET /mailers

Returns a list of mailer profiles that can be used.

### POST /email

 * **string** mailer
 * **string** subject
 * **string** textContent
 * **string** htmlContent
 * **array** sender
 * **array** receiver
 * **array** bcc
 * **array** cc
 * **array** attachments
 * **array** headers
 * **options** options
