moodle_filter_readability
=========================
Built on top of the urltolink filter this filter uses the readability API (freely avalible for non-commercial use) to grab page title, image and description to display an attactive informative link.
Please note this is an early release that does work on 2.6 but I doubt conforms to the moodle coding guidelines.

<strong>Wish list:</strong></br />
- When clicking link to load up a 'readability formatted' version of the web page within a pop-up/lighbox

<h3> To install </h3>
<ol>
<li>Create an account on readibility.com and generate an API key (it's under Settings > Account & scroll down the page to API Keys)</li>
<li>Place the files from this repository into your moodle filter directory</li>
<li>3) visit yourmoodlesite/admin to install and enter your API key</li>
</ol>

<p>If this looks interesting please fork and further develop, i'm reaching my limit of php knowledge. - Thanks!</p>

<h3>Known bugs</h3>
<ul>
<li>Does not play nicely with the default urltolink filter, please disable at site level for this to work</li>
<li>the 'Exclude domain' field must have a value to work - you can put anything in it</li>
</ul>


<h3>Credits:</h3>
<p>As with many things in moodle this filter uses much of the URL identification and formatting code created by David Mudrak - thankyou sir!</p>
<p>and uses API's from Readibility.com & g.etfv.co</li>
