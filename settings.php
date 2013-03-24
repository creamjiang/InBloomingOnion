<?php

const CLIENT_ID     = 'dJxiOUTHDr';
const CLIENT_SECRET = 'FWw6vVyAM2THRN8Zem1E816SNrxFXV2289EJ5G5SbqIVNOiN';
const REDIRECT_URI           = 'http://dev.inbloomingonion.local'; // This must match your app settings in SLC App Registration exactly!

const AUTHORIZATION_ENDPOINT = 'https://api.sandbox.slcedu.org/api/oauth/authorize';
const TOKEN_ENDPOINT         = 'https://api.sandbox.slcedu.org/api/oauth/token';

// Note:  Windows PHP instances have issues with the SSL certificate that is returned from the sandbox.
//        If you are running Windows against the sandbox, you may want to switch the flag below to TRUE.
//        Use at your own risk and ONLY in sandbox (not production) usages of the API.  In production,
//        please fix the issue with CA certifications on your server and set this flag to FALSE.
const DISABLE_SSL_CHECKS = FALSE;

?>
