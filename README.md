## About
> [Mobilant](http://mobilant.de/) is an ideal platform for sending sms, using this very simple API.

This is a composer package for using the [Mobilant](http://mobilant.de/) REST API. Have a look at their API docs for more details about all the parameters.

## minimum configuration
Fastest possible usage for PHP 5.4+ using chaining for a [fluent interface](http://en.wikipedia.org/wiki/Fluent_interface)
```php
(new \Econic\Mobilant\SMS)
	->setKey('myTokenKey123')
	->setMessage('hello world')
	->setTo('0049987123456')
	->setFrom('Bob')
	->setRoute('lowcostplus')
	->send();
```
Fastest possible usage for < PHP 5.4 and without using chaining
```php
$mobilant = new \Econic\Mobilant\SMS();
$mobilant->setKey('myTokenKey123');
$mobilant->setMessage('hello world');
$mobilant->setTo('0049987123456');
$mobilant->setFrom('Bob');
$mobilant->setRoute('lowcostplus');
$mobilant->send();
```
## Methods

Every setter returns the object again to enable chaining and has a respective getter

**setKey**
Your app API key

**setMessage**
The message of the sms

**setTo**
The receiver's phone number

**setFrom**
The sender name

**setRoute**
The route to choose

**setRef**
The reference to save with the message

**setConcat**
If the message should be concatenated when > 160 chars

**setSenddate**
The date to send if you want to time it for later

**setDebug**
If you want to act as if you'd drop a message but don't want it to be sent

**send**
Send the text message

*Returns* array with two indexes:

'success' => true if the message was sent successfully, otherwise false

'response' => An array with the data returned by the mobilant api if the message was sent successfully, otherwise null. Indexes are responsecode, message_id, cost, count