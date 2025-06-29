const querystring = require('querystring');
const axios = require('axios');

//Make Object with params
const data = {
      client_id: '606758118895327',
      client_secret: 'def9bf51da30820e036b4627324397bd',
      grant_type: 'authorization_code',
      redirect_uri: 'https://viviashop.com/instagram/callback',
      code: 'AQAIykMecZiZml3eoK65Y5Senmja5ix6WOY_NPiHxAjlptp7KrZW9vGAip7ByJJwfK234dCB71Aan6GfPGTutaiuwZlC_EBCMAiwstskfJ8Vda6nRxZDCa8nd2hZRxl11ZPzwfeE7dB0TXHOO-ZPJDq7UC1ukb9jjOpLuyZVrN0CjvaA6c0wk5B4PsX2nlMSHVUh_beZRmCVJdlUmAmf5X1xSSF3yQjOK5DlY_gi-wV-eA'
}



 axios.post("https://api.instagram.com/oauth/access_token", querystring.stringify(data))
.then(function (response) {
      console.log("OK", response.data);
 })
 .catch(function (error) {
      console.log(error);
 });

// axios.get("https://graph.instagram.com/v23.0/me?fields=user_id,username&access_token=IGAAIn1ZBIkLt9BZAFBnYzB4dHZA4VVlhQ3Y0b1JzQnQyQ2U2d2dNMVM5WjlWbFJXaUtDVlduV0FNYTByRXZAiUW9CM09zaklEeHhWMThETlZAzYmZAlZAm1SU1hqU0w1UlVqMFJ2ZAGpuaTJQQ2JvaEZACZA0FlOUtn")
// .then(function (response) {
//       console.log("OK", response.data);
// })
