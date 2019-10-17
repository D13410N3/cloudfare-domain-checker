# cloudfare-domain-checker
Simple PHP-script for checking "paid-till" date of domains hosted on Cloudflare
It uses "whois" binary, so you need access to your shell using PHP-function shell_exec()


1) Go to https://dash.cloudflare.com/profile/api-tokens and get your new API-token

2) Edit lines 3 & 4 in the script and enter your API key & email

3) Use this script as you want


As a result you'll get JSON-string looking like this:

[
    {
        "domain": "2012-2016.ru",
        "paid_till": "2020-08-02",
        "days_left": 289,
        "status": "NORMAL"
    },
    {
        "domain": "2ch.ovh",
        "paid_till": "2020-07-02",
        "days_left": 258,
        "status": "NORMAL"
    },
    {
        "domain": "4nmv.ru",
        "paid_till": "2020-03-17",
        "days_left": 151,
        "status": "NORMAL"
    },
    ....
    
NORMAL is 30 days and more
MINOR is 7-30 days
MAJOR is 2-7 days
CRITICAL is < 2 days

Depends on your system locale settings it can or can't work with cyrillic domains
