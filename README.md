moodle_filter_readability
=========================
Built on top of the urltolink filter this filter uses the readibility API (freely avalible for non-commercial use) to grab page title, image and description to display and attactive informative link.
Please note this is an early release that does work on 2.6 but I doubt conforms to the moodle coding guidelines.

Wish list:
- When clicking link to load up a 'readibiltiy formatted' version of the web page within a pop-up/lighbox

<strong> To install </strong>
1) Create an account on readibility.com and generate an API key (it's under Settings > Account & scroll down the page to API Keys)
2) Place the files from this repository into your moodle filter directory
3) visit yourmoodlesite/admin to install and enter your API key

If this looks interesting please fork and further develop, i'm reaching my limit of php knowledge. - Thanks!


<strong>Credits:</strong>
As with many things in moodle this filter uses much of the URL identification and formatting code created by David Mudrak - thankyou sir!
and uses API's from Readibility.com & g.etfv.co
