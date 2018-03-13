Ip Geo Example
==============

RUN STEPS
1. git clone https://github.com/artistlabs/ipgeoexample.git
2. composer install
3. from root project dir `php -S localhost:8888 -t src/public src/public/index.php`

usage
-----
URI /ip2geo?ip=x.x.x.x

example request:
`/ip2geo?ip=1.9.1.1`

example response: 
`{"city":"Subang Jaya","region":"Selangor","country":"Malaysia","latitude":"3.0438","longitude":"101.5806"}`