<html xmlns="http://www.w3.org/1999/xhtml"
  xmlns:fb="https://www.facebook.com/2008/fbml">
  <head>
    <title>Request Example</title>
  </head>

  <body>
    <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <p>
      <input type="button"
        onclick="sendRequestToRecipients(); return false;"
        value="Send Request to Users Directly"
      />
      <input type="text" value="User ID" name="user_ids" />
      </p>
    <p>
    <input type="button"
      onclick="sendRequestViaMultiFriendSelector(); return false;"
      value="Send Request to Many Users with MFS"
    />
    </p>
    
    <script>
      FB.init({
        appId  : '512075685472594',
        frictionlessRequests: true
      });

      function sendRequestToRecipients() {
        var user_ids = document.getElementsByName("user_ids")[0].value;
        FB.ui({method: 'apprequests',
          message: 'Request',
          to: user_ids
        }, requestCallback);
      }

      function sendRequestViaMultiFriendSelector() {
        FB.ui({method: 'apprequests',
          message: 'Prueba dialog request'
        }, requestCallback);
      }
      
      function requestCallback(response) {
        console.log(response);
      }
    </script>
  </body>
</html>