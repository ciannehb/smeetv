<html>
  <head>
    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAKlKiCqyx8fZZC5LX-WwfuhSWEHPq0j07xJtOMnW92FGrK-z8KBT_cJ5QFjAXCUB8B8nsQk1Zcw0Eeg"></script>
    <script type="text/javascript">

    google.load("feeds", "1");

    function initialize() {
      var feed = new google.feeds.Feed("http://twitter.com/statuses/public_timeline.rss");

feed.load(function(result) {
  if (!result.error) {
    var container = document.getElementById("feed");
    for (var i = 0; i < result.feed.entries.length; i++) {
      var entry = result.feed.entries[i];
      var div = document.createElement("div");
      div.appendChild(document.createTextNode(entry.title));
      container.appendChild(div);
    }
  }
});


    }
    google.setOnLoadCallback(initialize);

    </script>
  </head>
  <body>
    <div id="feed"></div>
  </body>
</html>
