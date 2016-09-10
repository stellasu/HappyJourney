# HappyJourney

What is this
-------------
<p>HappyJourney is a mobile-friendly web application built for travel agent. It has an Application module for the website visitors and an Administration module built for the administrator. This is specifically for Chinese users.</p>
<p>In Application side, customers can: </p>
<ul>
<li>Read introductions of attractions</li>
<li>Submit a request to customize travel plans</li>
<li>View and choose a shuttle itinerary, or submit a request to customize their own itineraries</li>
</ul>
<p>In Administration side, the administrator can: </p>
<ul>
<li>Safely log in as an administrator</li>
<li>Update text descriptions for the website</li>
<li>Add a new attraction, and manage the existing attractions</li>
<li>Add a new itinerary, and manage the existing itineraries</li>
<li>View and manage customer-submitted shuttle itineraries and requests</li>
</ul>

How to use
----------
<p>Please run script/builddb.sql to build the database which is configured in the code. and insert testing data. To add new administrator, please insert 'Username' and 'Password' into the 'Administration' table.</p>
<p>To visit the Application side, please go to <I>http://[site base url]/</I></p>
<p>To visit the Administration side, please go to <I>http://[site base url]/administration</I></p>

Built with
----------
Zend Framework 2

Video Demo
----------
https://drive.google.com/file/d/0B24RUusCOQNCYXppdE1rZUZqOXc/view


