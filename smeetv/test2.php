<script type="text/javascript" language="javascript">
var xmlstring = '\
<rss version="2.0">\
<channel>\
   <title>Weather Underground - Rome, Italy</title>\
   <link>http://www.wunderground.com/</link>\
   <description>Weather Underground RSS Feed</description>\
   <language>EN</language>\
   <image>\
      <url>http://icons.wunderground.com/graphics/smash/wunderTransparent.gif</url>\
      <link>http://www.wunderground.com</link>\
      <title>Weather Underground</title>     \
   </image>\
   <category>weather</category>\
   <item>\
      <title>Rome, Italy Current Conditions - 5:45 PM CEST Oct. 21</title>\
      <link>http://www.wunderground.com/global/stations/16239.html</link>\
      <description>Temperature: 64&#176;F / 18&#176;C | Humidity: 100% | \
               Pressure: 29.92in / 1013hPa | Conditions: Mostly Cloudy | \
     Wind Direction: South | Wind Speed: 7mph / 11km/h | Updated: 5:45 PM CEST\
      </description>\
      <pubDate>Fri, 21 Oct 2005 15:45:00 GMT</pubDate>\
   </item>\
</channel>\
</rss>';

// convert string to XML object
var xmlobject = (new DOMParser()).parseFromString(xmlstring, "text/xml");

// get a reference to the root-element "rss"
var root = xmlobject.getElementsByTagName('rss')[0];
// get reference to "channel" element
var channels = root.getElementsByTagName("channel");
// now get all "item" tags in the channel
var items = channels[0].getElementsByTagName("item");
// in the "item" we have a description, so get that
var descriptions = items[0].getElementsByTagName("description");
// we also get the "pubDate" element in the "item"
var date = items[0].getElementsByTagName("pubDate");

// get the actual description as string
var desc = descriptions[0].firstChild.nodeValue;
// split the string - temperature is element #1 -> 0
var descarray = desc.split("|");
var temperature = descarray[0];
// same for the pubDate
var update = date[0].firstChild.nodeValue;
var tooltipstring = update + ": " + descarray[3];

// now we have the RSS data we want as strings - do something now :-)
alert(temperature);
alert(tooltipstring);
</script>
