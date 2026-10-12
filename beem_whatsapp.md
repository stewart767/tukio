MOJA DOCUMENTATION
INTRODUCTION
The Beem Moja service is an all-in-one communication platform for sales and service. It lets customers interact seamlessly with your business across any channel: WhatsApp, Google Business Messaging, Facebook, Instagram & SMS

Initial Setup
Create a free account on https://login.beem.africa

On completing your registration you will get a confirmation email. Click on the link or paste the link into your browser to validate the account.

Log into your account with your username & password on https://login.beem.africa.

Select Create Channel from the channels menu and fill up the channel details.

Beem team will work on activating the channel.

For automated bots contact our support team.

Obtaining & Managing Your API Credentials
Visit Profile Menu and Select Authentication Information.

Click generate API Key and Secret Key. Note that the Secret is only displayed once, so please store this safely.

Use this API Key and Secret Key to start sending messages using the API defined below.

You can visit the profile menu again at any time to disable this API Key and regenerate a new API Key and Secret.

ACTIVE SESSIONS
Get list of Active Sessions
get
https://apichatcore.beem.africa/v1/chatapi/active-users
HEADERS
Field	Type	Description
api_key	string	
Basic Authentication Username

secret_key	string	
Basic Authentication Password

Content-Type	string	
application/json => This is used for json request

Succcess Response
HTTP/1.1 200 OK 
[
    {
        "sesssion_start_time": "2022-08-26T08:31:00.172Z",
        "channel": "whatsapp",
        "from_addr": "255701000000",
        "username": "test",
        "last_message": "Hello"
    },
    {
        "sesssion_start_time": "2022-08-25T10:19:37.960Z",
        "channel": "whatsapp",
        "from_addr": "255700000000",
        "username": "bot",
        "last_message": "Hi"
    },
]
SENDING MESSAGE
Moja API will allow users to send message to active sessions only.

An active session stipulates a 24-Hour Rule, according to which businesses are restricted to send messages only to those users who have interacted with the businesses via messaging channels within the last 24 hours.

post
https://apichatcore.beem.africa/v1/chatapi
HEADERS
Field	Type	Description
api_key	string	
Basic Authentication Username

secret_key	string	
Basic Authentication Password

Content-Type	string	
application/json => This is used for json request

BODY
Field	Type	Description
from	string	
Whatsapp Business Number

to	string	
Customer Phone Number

channel	string	
The channel used to send message to, Can be 'whatsapp', 'facebook', 'instagram' or 'google_business_messaging'

callback_urloptional	string	
Callback URL to receive message status. Example: https://example.com/callback

transaction_idoptional	string	
Unique transaction id for reference. It should be a UUIDv4

message_type	string	
Type of message. Can be 'text', 'image' or 'document'

textoptional	string	
Text Message

documentoptional	object	
JSON object containing details of the document such as pdf, doc, csv

imageoptional	object	
JSON object containing details of the image such as jpeg, jpg, png

audiooptional	object	
JSON object containing details of the audio

videooptional	object	
JSON object containing details of the video

locationoptional	object	
JSON object containing details of the location

Image Object
Field	Type	Description
mime_type	string	
image mime-type

url	string	
image url

document Object
Field	Type	Description
mime_type	string	
document mime-type

url	string	
document url

Video Object
Field	Type	Description
mime_type	string	
document mime-type

url	string	
document url

Location Object
Field	Type	Description
latitude	string	
latitude

longitude	string	
longitude

SAMPLE REQUESTS
post
https://apichatcore.beem.africa/v1/chatapi
Text
Image
Document
Video
Location
{
    "from": "255701000000",
    "to": "255701000001",
    "channel": "whatsapp",
    "transaction_id": "12345",
    "message_type": "text",
    "text": "Hello there"
}
SAMPLE SCRIPTS
post
https://apichatcore.beem.africa/v1/chatapi
PHP
NODE-JS
<?php

$url="https://apichatcore.beem.africa/v1/chatapi";

$api_key='<api_key>';
$secret_key = '<secret_key>';

$from = '<from>';
$to = '<to>';
$channel = '<channel>';
$transaction_id = '<transaction_id>';
$message_type = '<message_type>';
$callback_url = '<callback_url>';
$text = '<text>';

$body = array('from' => $from, 'to' => $to, 'channel' => $channel, 'transaction_id' => $transaction_id, 'message_type' => $message_type, 'callback_url, ' => $callback_url, 'text, ' => $text );


$ch = curl_init($url);
$option = array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($body));

error_reporting(E_ALL);
ini_set('display_errors', 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt_array($ch,$option);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

var_dump($response);
SAMPLE RESPONSE
post
https://apichatcore.beem.africa/v1/chatapi
Succcess Response
Error Response
HTTP/1.1 200 OK 
{
   "message": "success"
}
RECEIVING MESSAGE
Moja API allow users to receive message from their respective messaging channels through registered callback url in their web profile settings.


How to register a callback url in your web profile settings

Visit the settings menu here
Select conversation tab
Register your callback and save
post
https://example.com/callback
BODY
Field	Type	Description
from	string	
Customer Phone Number

to	string	
Whatsapp Business Number

channel	string	
The channel used to send message to, Can be 'whatsapp', 'facebook', 'instagram' or 'google_business_messaging'

transaction_id	string	
Beem unique transaction id for reference.

message_type	string	
Type of message. Can be 'text', 'image' or 'document'

textoptional	string	
Text Message

documentoptional	object	
JSON object containing details of the document such as pdf, doc, csv

imageoptional	object	
JSON object containing details of the image such as jpeg, jpg, png

audiooptional	object	
JSON object containing details of the audio

videooptional	object	
JSON object containing details of the video

contactsoptional	object	
JArray of SON object containing details of the contact

locationoptional	object	
JSON object containing details of the location

Image Object
Field	Type	Description
mime_type	string	
image mime-type

url	string	
image url

captionoptional	string	
image caption

document Object
Field	Type	Description
mime_type	string	
document mime-type

url	string	
document url

Video Object
Field	Type	Description
mime_type	string	
document mime-type

url	string	
document url

captionoptional	string	
video caption

Location Object
Field	Type	Description
latitude	string	
latitude

longitude	string	
longitude

Contacts Object
Field	Type	Description
names	string	
names

phones	string	
phones number

SAMPLE RECEIVING REQUESTS
post
https://example.com/callback
Text
Image
Document
Video
Location
{
   "from": "255701000000",
    "to": "255701000000",
    "channel": "whatsapp",
    "transaction_id": "12345",
    "message_type": "text",
    "text": "Hello there"
}
WHATSAPP TEMPLATES
WhatsApp Template Overview
WhatsApp templates are special types of messages used by businesses to initiate or re-engage conversations with customers through the WhatsApp channel. These templates are necessary for starting a conversation with a user either for the first time or after 24 hours have passed since the last interaction.

Types of WhatsApp Templates
Templates are categorized based on their purpose and supported features. Below are the main types:

Marketing Templates:
Used for promotional or non-transactional messages like announcements, offers, or product recommendations.
Supports text, rich media (images, videos, documents), and carousels.
Utility Templates:
Used for transaction-related messages such as delivery updates, account changes, or appointment reminders.
Supports text, media, and carousels.
If combined with marketing content, the entire message is treated as marketing.
Authentication Templates:
Used for sending one-time passwords (OTPs) during user verification processes.
Includes verification codes, optional security notices, and expiration warnings.
Supports special buttons like “Copy Code” and “One-Tap Autofill” (Android only).
Limited to plain text content with no emojis, URLs, or media in the message body.
Requesting a WhatsApp Template
Before sending template-based messages, a business must first request approval for a template. There are two ways to submit a request:

Via the Engage Portal:
Log in to the Engage Portal.
If you have registered for WhatsApp Broadcast, expand the options and select WhatsApp Templates.
Click the Create New button, fill in the required fields, and submit the form.
Via Moja:
Navigate to the Settings page.
Select WhatsApp Templates.
Click the Create New button, fill in the relevant data, and submit your request.
Interacting with WhatsApp Templates via API
There are two key API endpoints available for interacting with WhatsApp templates:

Fetch Template List: Retrieve a list of all your requested WhatsApp templates.
Send Template-Based Message: Use this endpoint to send a message based on an approved WhatsApp template.
FETCH TEMPLATES LIST
GET TEMPLATES
Retrieve a paginated list of WhatsApp message templates. This endpoint supports flexible filtering by various query parameters, making it easier for developers to manage, audit, and utilize templates programmatically.

Purpose
This endpoint is used to fetch a list of all WhatsApp message templates that have been created for your account. These include templates that are in any state — pending, approved, rejected, or failed — and belong to categories like AUTHENTICATION, UTILITY, or MARKETING.

get
https://apichatcore.beem.africa/v1/message-templates/list?name=promo&category=MARKETING&q=button&status=approved&page=1
HEADERS
Field	Type	Description
api_key	string	
Basic Authentication Username. See API Authentication for more details.

secret_key	string	
Basic Authentication Password. See API Authentication for more details.

Content-Type	string	
application/json => This is used for json request

PARAMATERS
Field	Type	Description
name	string	
Filters templates by their exact name. Useful when searching for a specific template.

category	string	
Specifies the type of template. Acceptable values: AUTHENTICATION, UTILITY, or MARKETING.

q	string	
A wildcard search based on multiple fields like name, content, header, footer, buttons, and id. This allows flexible searching across template attributes.

status	string	
The status of the template. Must be one of the following: pending, approved, rejected, or failed. This helps filter templates based on their approval status.

page	string	
The page number to retrieve, allowing for pagination of the template list. Must be an integer.

RESPONSE
Field	Type	Description
id	string	
This is the unique id of the template generated by Beem after a template is created. It will be used when submitting a template-based message.
Note: Not to be confused with template_id generated from the WhatsApp end

template_id	string	
This is the unique id of the template generated by Meta (WhatsApp) after approval.

facebook_template_id	string	
This is the ID of the template on Facebook/Meta's platform, representing the approved template for WhatsApp.

name	string	
The name of the template created.

category	string	
The category of the template (e.g., AUTHENTICATION, UTILITY, MARKETING).

type	string	
The type of template created, such as text, media, etc.

status	string	
The status of the created template, i.e. pending, approved, rejected, or failed.

botId	string	
The unique bot ID. The bot ID will be used as user_id when sending the template to a user.

language	string	
The language of the template (e.g., en for English, ca for Catalan).

content	string	
The content of the message template, which is the actual message sent to users.

mediaUrl	string	
If the template contains media, this will be the URL of the media file.

mimeType	string	
If the template contains media, this will be the MIME type of the media file. Accepted types are: application/pdf, video/mp4, video/mpeg, application/msword, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.presentationml.presentation, text/csv.

buttons	object	
Buttons included in the template, such as call-to-action buttons or quick replies.

footer	string	
The footer of the message template, which may contain additional information or disclaimers.

header	string	
The header of the message template, usually used to give a title or summary for the message.

metadata	array	
Metadata of the template, including flags for failed, pending, approved, and rejected statuses.

createdAt	string	
The timestamp when the template was created, in ISO 8601 format.

updatedAt	string	
The timestamp when the template was last updated, in ISO 8601 format.

pagination	object	
Pagination data for the list of templates, including:

totalItems: The total number of templates in the result set.
currentPage: The current page of the results being displayed.
totalPages: The total number of pages available based on the total items and page size.
Success Response
Sample Authentication Template
Sample Marketing/Utility Text Template
Sample Marketing/Utility Media(Document) Template
Sample Marketing/Utility Media(Image) Template
Sample Marketing/Utility Media(Video) Template
HTTP/1.1 200 OK
        {
          "data": [
            {
              "id": 10913,
              "template_id": "fbce0d08-4ecb-456c-9c13-a709767396531",
              "facebook_template_id": "107484765349667",
              "type": "TEXT",
              "category": "AUTHENTICATION",
              "name": "auth_template_api1",
              "botId": "9a87df7c-0fcd-42fb-9a69-66c58ff66a92",
              "status": "",
              "language": "en_US",
              "content": "{{1}} is your verification code. For your security, do not share this code.",
              "mediaUrl": null,
              "mimeType": null,
              "buttons": [
                {
                  "type": "OTP",
                  "otp_type": "COPY_CODE",
                  "text": "COPY OTP"
                }
              ],
              "footer": "The code will expire in 1min",
              "header": null,
              "metadata": [
                {
                  "failed": true,
                  "pending": true,
                  "approved": false,
                  "rejected": false
                }
              ],
              "createdAt": "2024-09-05T09:01:22.000Z",
              "updatedAt": "2024-09-05T10:48:53.000Z"
            },
            {
              "id": 10912,
              "template_id": "c2f932fa-ecd4-4b3f-96b8-53e278b02f2e",
              "facebook_template_id": "107484721349667",
              "type": "TEXT",
              "category": "AUTHENTICATION",
              "name": "auth_ui_test",
              "botId": "9a87df7c-0fcd-42fb-9a69-66c58ff66a92",
              "status": "enabled",
              "language": "en_US",
              "content": "{{1}} is your verification code. For your security, do not share this code.",
              "mediaUrl": null,
              "mimeType": null,
              "buttons": [
                {
                  "type": "OTP",
                  "otp_type": "COPY_CODE",
                  "text": "COPY OTP"
                }
              ],
              "footer": "The code will expire in 1min",
              "header": null,
              "metadata": [
                {
                  "failed": false,
                  "pending": true,
                  "approved": true,
                  "rejected": false
                }
              ],
              "createdAt": "2024-09-05T08:44:52.000Z",
              "updatedAt": "2024-09-05T08:45:02.000Z"
            },
            {
              "id": 10911,
              "template_id": "a446bab9-9d2b-4144-8e3d-86940a4c99533",
              "facebook_template_id": "107484765333667",
              "type": "text",
              "category": "MARKETING",
              "name": "markting_api_test",
              "botId": "9a87df7c-0fcd-42fb-9a69-66c58ff66a92",
              "status": "",
              "language": "en_US",
              "content": "From your account saving {{1}}received free {{2}}",
              "mediaUrl": null,
              "mimeType": null,
              "buttons": [
                {
                  "type": "PHONE_NUMBER",
                  "text": "number",
                  "phone_number": "255782270052"
                }
              ],
              "footer": "by powered me",
              "header": "Hi {{1}}",
              "metadata": [
                {
                  "failed": false,
                  "pending": true,
                  "approved": true,
                  "rejected": false
                }
              ],
              "createdAt": "2024-09-05T06:48:49.000Z",
              "updatedAt": "2024-09-05T10:49:53.000Z"
            }
          ],
          "pagination": {
            "totalItems": 150,
            "currentPage": 1,
            "totalPages": 6
          }
        }
Error Response 1
Error Response 2
HTTP/1.1 401 Unauthorized
{
   "code": 401,
   "message": "Invalid Authentication Parameters"
}
SENDING TEMPLATES
This endpoint allows you to send pre-approved WhatsApp message templates via Beem’s WhatsApp channel. It supports sending templates with or without media, interactive buttons, and personalized placeholders for dynamic content (like {{1}}, {{2}}, etc.).

Each message template must be pre-approved by WhatsApp and created via the Engage Portal or Moja. Once a template is approved, you can reference it using the messageTemplateData.id.

The destination_addr field accepts an array of objects, each containing a phoneNumber and a params array. These parameters are automatically substituted into your template’s placeholders in the order they appear (e.g., {{1}}, {{2}}).

If the template contains media (like a PDF, image, or video), provide the media URL via the content.mediaUrl field. Optionally, include additional metadata like headers and footers within the template itself.

On successful execution, the API returns detailed information about valid and invalid recipients, credit usage, and the jobId used to track this campaign.

post
 https://apibroadcast.beem.africa/v1/broadcast/template/api-send
HEADERS
Field	Type	Description
api_key	string	
Basic Authentication Username. See API Authentication for more details.

secret_key	string	
Basic Authentication Password. See API Authentication for more details.

Content-Type	string	
application/json => This is used for json request

PARAMATERS
Field	Type	Description
from_addr	string	
The WhatsApp number of your Beem channel, which acts as the sender.

destination_addr	array	
An array of objects, each containing the recipient's phone number and the dynamic parameters to be substituted into the template. For authentication templates, the params array is typically used to provide dynamic data such as a one-time password (OTP), a verification code, or a secret code that corresponds to placeholders like {{1}} in the message template.

channel	string	
The messaging platform, which should be whatsapp.

contentoptional	JSON	
Message metadata e.g mediaUrl.

messageTemplateData	JSON	
Message template metadata.


This field should contain the id of the pre-approved WhatsApp template you wish to use. This ID is returned from the template listing endpoint (/v1/message-templates/list) and must correspond to a template that is approved and active.


Example:


{

                 "id": 10913

                    }

The template ID ensures that the correct template is selected and allows the system to match placeholders with the corresponding params defined in the destination_addr array.

RESPONSE
Field	Type	Description
statusCode and successful	string	
Help determine if the request was successfully processed.

message	string	
A response message containing dynamic values represented by placeholders ({{1}}, {{2}}, etc.), which are replaced with actual data.

validation	object	
The validation object gives a breakdown of valid and invalid numbers, and associated parameters.

  validCounts	number	
The count of valid phone numbers processed in the API call.

  validNumbers	array	
An array of valid phone numbers and associated parameters.

  invalidCounts	number	
The count of invalid phone numbers processed in the API call.

  invalidNumbers	array	
An array of invalid phone numbers (empty if no invalid numbers are found).

    phoneNumber	string	
The valid phone number processed.

    params	array	
An array of parameters associated with the valid phone number (e.g., TestParam1).

credits	object	
An object containing the price breakdown for the API request.

  priceBreakDown	array	
An array of objects representing the cost details per country.

  totalPrice	number	
The total cost for all sendouts across all countries.

    country_code	string	
The country code for which the cost is calculated (e.g., 254 for Kenya).

    total_sendouts	string	
The total number of messages sent out to this country.

    ratePerSendout	number	
The rate charged per message sent to this country.

    price	number	
The total price calculated for the sendouts in this country.

jobId	string	
A unique identifier for the API job. This ID can be used to track the status of the message batch or any subsequent related API calls.

MARKETING/UTILITY TEMPLATE BODY
AUTHENTICATION TEMPLATE BODY
RESPONSE
{
    "from_addr": "2557******45",
    "destination_addr": [
        {
            "phoneNumber": "2547******06",
            "params": [
                "TestParam1"
            ]
        },
        {
            "phoneNumber": "2547*****279",
            "params": [
                "TestParam1"
            ]
        }
    ],
    "channel": "whatsapp",
    "content": {
        "mediaUrl": "http://example-image.com"
    },
    "messageTemplateData": {
        "id": 1024
    }
}
error codes
Failed Responses
Unauthorized token
Invalid User/channel settings
Invalid recipient/destination address
Invalid template id
{
    "statusCode": "401",
    "message": "No authorization headers"
}
WHATSAPP TEMPLATES DLRs
WhatsApp Template DLRs Callback
Delivery Reports (DLRs) provide real-time status updates on the delivery of WhatsApp message templates. These confirmations help track whether messages have been delivered, read, or failed. In our service, we enable clients to receive these DLRs directly through their own custom callback URLs. By specifying a unique URL, clients can seamlessly integrate and receive delivery confirmations within their systems, allowing for better tracking and management of WhatsApp messaging campaigns.

Adding custom URL
Before receiving reports, you must first add a callback URL. The endpoint must return a 200 status on a GET request, as we use it to check whether the callback is active. To add your callback URL:

Via the Engage Portal:
Log in to the Engage Portal.
If you have registered for WhatsApp Broadcast, expand the options and select API Setup.
Paste your callback url and click Save button.
sample response
Successful Responses
Successful Response
{ 
 "broadcast_id": "broadcastid_123123", 
 "message_id": "msgid_123123", 
 "status": "read", 
 "destination": "255******581", 
 "message": "this is the message sent",
 "timestamp": "2023-06-26 02:31:29". 
}
error codes
Failed Responses
User not found
Callback Unreachable
Callback URL not configured
{
    "statusCode": "103",
    "message": "No user found for the given integration ID"
}