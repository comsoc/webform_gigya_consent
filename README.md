## Webform Gigya Consent 8.x-1.0

### About

This module provides a Webform checkbox element which tracks consent
with Gigya's Consent API. A common use case is add a Privacy Policy
agreement checkbox with this module where visitor consent needs to be
tracked with Gigya.

This module requires the Webform module
(https://www.drupal.org/project/webform).

### Installation

1. Copy/upload the Webform Gigya Consent module to the modules directory of
   your Drupal 8 installation.

2. Enable the 'Webform Gigya Consent' module in 'Extend' (/admin/modules).

3. Configure the Gigya Consent API settings at
   /admin/config/webform_gigya_consent/settings. Your Gigya administrator can
   provide this information.

### Usage

1. Make or edit an existing Webform at /admin/structure/webform.

2. Your Webform must have an Email field which will contain the email address
   of the consenting party. This field should be required.

3. Select 'Add Element' on your Webform and add the Webform Gigya Consent
   element which can be found under the 'Gigya' section.

4. When configuring the Webform Gigya Consent field, in the 'Title' enter the
   text of your checkbox such as "I have read and agree to the privacy policy."

   You can provide a link in this text by enclosing the linked text
   in curly brackets. For example, to link the words 'privacy policy'
   write the title as "I have read and agree to the {privacy policy}."

5. If you need to provide a link as described in step 4, enter its URL
   in the 'Link' field.

6. In the 'Gigya Consent Settings' section, choose your Webform's field which
   contains the consenting party's email address.

7. Enter the 'Gigya Consent Name.' This is an internal name used by your Gigya
   installation. Typically, each consent policy will have its own name - for
   example PrivacyPolicy, TermsAndConditions, etc.

8. You can set other Webform settings for this field such as help text or
   making the field required as needed.

9. Save the element and save the Webform.
