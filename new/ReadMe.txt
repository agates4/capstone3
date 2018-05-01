register.php

  - Get new user information from front end, insert them into database to create a user account. 
  - If there is already a user who uses the same email, show error message.

  - Parameters & return values
  
  	POST variables
        $_POST["username"]      - user name
        $_POST["email"]         - user email
        $_POST["password"]      - user password
    Return
        "message"               - success or error message

login.php

  - Get user information from front end, check database if there is a match with that information dand pass the result back to front end.

	- Parameters & return values
	  
	  POST variables
        $_POST["email"]         - user email
        $_POST["password"]      - user password
    Return
        JSON
            "message"           - success or error message
            "user_id"           - user id number

inserrt_new_poi.php

		- get poi information from the front end and insert it into datbase.

		- Parameters & return values
		
			POST Variables
          $_POST["user_id"]       - user id number
          $_POST["latitude"]      - new poi location latitude
          $_POST["longitude"]     - new poi location longitude
          $_POST["name"]          - new poi name
          $_POST["description"]   - new poi description
      Return
          message
              tells if new poi inserted successfully
              tells if new ownership inserted successfully
get_user_pois.php
		
		- Get user id from front end, retrieve nearby (< 15miles) pois and pass them to front end

		- Parameters & return values

			POST Variables
          $_POST["user_id"]       - user's email
   
    	Return
          JSON
              "poi_id"            - poi id number
              "poi_name"          - poi name
              "poi_latitude"      - poi location latitude
              "poi_longituge"     - poi location longitude
              "description"       - poi description

update_poi.php

		- Get user poi information and update existing poi using them. 

		- Paramteters & return values
		
			POST Variables
          $_POST["poi_id"]        - poi id number to be updated
          $_POST["latitude"]      - modified poi location latitude
          $_POST["longitude"]     - modified poi location longitude
          $_POST["name"]          - modified poi name
          $_POST["description"]   - modified poi description
     Return
          message
              tells if poi updated successfully

