# Facebook Anonymous Publisher

A real-time anonymous publishing application for Facebook pages.

The general method to provide an anonymous Facebook page for people is:
> An administrator holds a Facebook page and provides a third-party service for people to post messages to a temeporay database. Once the administrator received a new message from a user, he/she has to manually review and publish the message to the Facebook page.


### Less Management, More Freedom of Speech

This project let you having an intelligent *bot* which is able to automatically perform the following jobs for you:

+ Without any exceptions and unpredictable errors from Facebook API, this *bot* will directly publish the new messages from users  to your Facebook page.
+ A message queuing service will automatically start when too many messages have created by users within a short period.
+ Anyone has the right to anonymously speak out his/her thoughts to the public through this application without need to login into his/her Facebook account or use any third-party applications.
+ You are able to set your filters and senstive words to prevent abuse using and spam messages from haters.
+ This *bot* works 24/7 without need to sleep.

## Demo
[Click here](http://demo-anonykangxi.rhcloud.com)

## How to use?

This application is based on Laravel 5.0 (a PHP framework) and free/open on Github.

If you don't have your own server, the following instructions will teach you how to build your own Facebook Publisher on [OpenShift](https://www.openshift.com) (by using the *Free Plan*).

### Step 1: Create a Facebook page
+ [Click here to create a new Facebook page](https://www.facebook.com/pages/create/), select appropriate page type, fill in description and other relevant fields.

+ In your new Facebook page, switch to `About` tab and scroll down to the bottom of the page then note down the `Facebook Page ID`. You will use the role and previlege of this Facebook page to post messages on this page's wall.

### Step 2: Create a Facebook App
+ If you were not a Facebook developer, [click here to register as a developer](http://developers.facebook.com) (You have to verify through mobile.)

+ Go to [Facebook Apps dashboard](https://developers.facebook.com/apps) ☛ Click `Add a New App` ☛ Choose platform of `Website` ☛ Choose a name for your application ☛ Click `Create New Facebook App ID` ☛ Choose Category ☛ Click `Create App ID`

+ Go back to [Apps dashboard](https://developers.facebook.com/apps) ☛ Select your new application ☛ `Settings` ☛ `Basic` ☛ Enter `Contact Email` ☛ `Save`

+ Go to `Status & Review` ☛ `Do you want to make this app and all its live features available to the general public?` toggle the button to `Yes` ☛ `Make App Public?`, click `Yes`

+ Go back to `Dashboard`, note down `App ID` and `App Secret` (You have to click `Show` next to the field; it will ask you to enter your Facebook password.)

### Step 3: Obtain your page access token
+ Go to [Graph API Explorer](https://developers.facebook.com/tools/explorer/) ☛ In the Application drop-down menu, select the name of your app which created in Step 2 ☛ Click `Get Token` to open drop-down menu and select `Get Access Token` ☛ In Permissions popup menu, go to `Extended Permissions` tab ☛ Checked  `manage_pages`, `publish_actions` and `publish_pages` ☛ Click `Get Access Token`

+ Note down the `short-lived token` which shows in the input field next to the Access Token label.

+ Next, we are going to convert short-lived access token to a long-lived token. Please fill in the corresponding values to the following URL and open this URL in a browser:
```
https://graph.facebook.com/oauth/access_token?
  client_id={APP_ID}&
  client_secret={APP_SECRET}&
  fb_exchange_token={SHORTLIVED_ACCESS_TOKEN}&
  grant_type=fb_exchange_token
```

+ You will see `access_token={...}`. This new access_token is the `long-lived token`, next, we are going to use it to get the page access token which will never expire in the future.

+ Go to [Graph API Explorer](https://developers.facebook.com/tools/explorer/) ☛ Paste the `long-lived token` into the Access Token input field ☛ Type `me/accounts` in the `Graph API` query input field ☛ Click `Submit` button ☛ You will see the information of all your pages, find the Facebook page created in Step 1 and note down the `access_token` of it.

+ According to [Facebook's documentation](https://developers.facebook.com/docs/facebook-login/access-tokens#extendingpagetokens), a page access token obtained from long-lived user token will never expire in the future.

### Step 4: Register an OpenShift account and deploy Publisher Application on it
+ Go to [Openshift create an account](https://www.openshift.com/app/account/new). Fill in your email and other required information ☛ Click `Sign Up` ☛ Then you will receive a verify mail (go checking your inbox), click `Verify Your Account` ☛ Click `I Accept` ☛ Click `Create your first application now` ☛ In the application type list, find `Laravel 5.0` and select it.

+ Fill in your `Public URL` where has two fields. The first one is a string for recognizing your application, the second is your namespace. For example, I choose **kangxi**-**kaobei**.rhcloud.com in this demo ☛ Next, copy an paste the following URL to the `Source Code` field:
```
https://github.com/kxgen/facebook-anonymous-publisher
```

+ Then click `Create Application` ☛ `Will you be changing the code of this application?` click `Yes, help me get started`

+ Next step, we have to generate a ssh key for OpenShift to identify your device, open `Terminal` and enter:
```
$ ssh-keygen -t rsa -b 4096
Generating public/private rsa key pair.
```

+ You will see a prompted message of `Enter a file in which to save the key`, please enter `id_rsa_openshift`, then hit enter again:
```
Enter file in which to save the key (/Users/yourname/.ssh/id_rsa): id_rsa_openshift
```

+ It will ask you to enter a passphrase, press enter to use empty passphrase:
```
Enter passphrase (empty for no passphrase):
Enter same passphrase again:
```

+ Next, it will show the fingerprint or id, of your SSH key. It will look something like the following message:
```
Your identification has been saved in id_rsa_openshift.
Your public key has been saved in id_rsa_openshift.pub.
The key fingerprint is:
SHA256:KR08ZVqrfZUU0O4nY3TDyWVgZ2SIIDg5iCaPpi9BDMz yourname@device.local
```

+ To review `id_rsa_openshift.pub`, you can use the following command:
```
$ cat ~/.ssh/id_rsa_openshift.pub
```

+ You will see something like below text, copy the texts:
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDYcnn3tA4B0GUM4SxNA2zilkTuMGPpzHg4YEUitabUSimts9H4PuFBU00/N3w2jPCifzAicT+LHRZ0e9ksnXvVKdwH3GMA6FG92/Xepq2/F3Be3+jRB9EtHNY6Q6e5FtGn8M7PzbOQ2LpuSH65IjOqfVWCifuNdD/yZYCVRrv7UH4FnBXcdVa1t6rglbb4mrQbS9V5XB60Qrkth/n/PmSQU0CJct1tG1VOx7MYa7dGjlN/LC//Cb0DIa7lIj7ZjF873zKJ7R5Y4hOOHklHaS6+x1rO1YMjmykKXHK+ifs/iJiJHmha5vb9An602szw1KqGodZBoduZLjkGMdRn5QZJ yourname@device.local
```

+ Go back to OpenShift screen ☛ `Paste the contents of your public key file (.pub)`, paste the texts which you has copied on the above step to this field ☛ Click `Save`

+ Now you can clone the repository to your computer, copy the `git clone` command and open your `Terminal` ☛ Paste the command and press enter

+ Click `Continue to the application overview page.` link ☛ Copy the `application URI` (for example, the URI in this demo is: *kangxi-kaobei.rhcloud.com*).

### Step 5: Register Google reCAPTCHA
+ Go to [Google reCAPTCHA](https://www.google.com/recaptcha/admin) ☛ Fill the `Label` with your application name ☛ Paste the application URI in Step 4 to the `Domains` input field ☛ In `Owners` field, enter your email ☛ Click `Register` ☛ note down the `Site key` and `Secret key`

### Step 6: Configure your Publisher settings
+ Go to the repository folder which you have cloned in Step 4 ☛ Open `./config/publisher.php` with your preferred editor and configure the application settings.

+ Then, open the `Terminal` and enter following commands:
```
$ cd /path/to/your/repo/folder
$ git add .
$ git commit -m 'update publisher settings'
$ git push
```

+ When the git push has done, go to your application dashboard `http://yourdomain/admin/setting` and configure the settings.
+ Go to your application homepage and try to submit a testing message.


### Step 7: Keep your Publisher working

+ Go to [Uptime Robot](http://uptimerobot.com) ☛ Click `Sign-up (free)` ☛ In the popup, fill in your Name, E-mail and Password ☛ Click `Sign-up` button ☛ Check your E-mail inbox, open the account activation email, click the activate link.

+ [Login to Uptime Robot](https://uptimerobot.com/login) ☛ In your dashboard, click `Add New Monitor` ☛ In popup, `Monitor Type` choose `HTTP(s)` ☛ In `Friendly Name` field, fill a monitor name you like ☛ `URL (or IP)` fill in your application URI ☛ Click `Create Monitor` button.


## Donate
If you want to donate there are 3 methods: PayPal, Bitcoin, or Pay2go.

#### PayPal
To donate through PayPal go to this [page](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SAWSQ2HGSFW22) and follow the instructions on the page.

#### Bitcoin
It's possible to use Bitcoin to donate. Just send the Bitcoins to this address: 14ABxmCme8LPkKS2KuWU9YrQCHMVCcnTt5

#### Pay2go
To donate through Pay2go go to this [page](https://web.pay2go.com/EPG/service_donate/0SvJFQ) and follow the instructions on the page.


## License
Facebook Anonymous Publisher is licensed under the [MPLv2 License](LICENSE.md).
