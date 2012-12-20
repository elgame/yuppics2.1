<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <body>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <div id="fb-root"></div>
    <script>
      // assume we are already logged in
      FB.init({appId: '512075685472594', xfbml: true, cookie: true});

      FB.ui({
          method: 'send',
          name: 'Yuppics',
          link: 'www.yuppics.com',
          });
     </script>
  </body>
</html>