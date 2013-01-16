<?php 

/**
 * Vigener Cipher class
 * 
 * Encrypts and decrypts a string on a given key
 * 
 * @pakcage Vigener
 */
class VigenerCipher
{

	/**
	 * The key to the encryption
	 * defaults to 'crypto'
	 * 
	 * @var string
	 */
	private $key = 'crypto';


	/**
	 * Alphabetical characters allowed
	 * 
	 * @var string
	 */
	private $useable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890 ';


	/**
	 * Special characters allowed
	 * 
	 * @var string
	 */
	private $specials = '!@#$%^&*()-_+={[}]|;:\'"<,>.?/~\`';

	/**
	 * Concatenates useable and specials on instantiation
	 * 
	 * @var string
	 */
	private $full_string = '';

	/**
	 * An associative array of key => value pair
	 * computed on instantiation eg: array('a' => 'a', 'b' => 'b');
	 * 
	 * It is used primarily for grabbing the correct value 
	 * from the useable array on encryption and decryption 
	 * 
	 * @var array
	 */
	private $use = array();


	/**
	 * Constructs the class with a key being given
	 * 
	 * @param null $key
	 * @return $this
	 */
	public function __construct($key = null)
	{
		$this->setKey($key);
		for ($i = 0; $i < strlen($this->useable); $i++) {
			$this->use[$this->useable[$i]] = $i+1;
		}
		$last = $i;
		for ($i = 0; $i < strlen($this->specials); $i++) {
			$this->use[$this->specials[$i]] = ($last + ($i+1));
		}
		// here is where to update the full_string if other characters
		// are added
		$this->full_string = $this->useable.$this->specials;
		return $this;
	}


	/**
	 * Ensure that the values used are allowed by the class
	 * 
	 * @param $string
	 * @throws VigenerException
	 */
	private function validate($string)
	{
		// validate the string that it meets the criteria
		$special_regex = '@^[A-Za-z0-9 ';
		// escape all specials
		for ($i = 0; $i < strlen($this->specials); $i++) {
			$special_regex.= '\\'.$this->specials[$i];
		}
		$special_regex.= ']+$@';
		if (!preg_match($special_regex, $string)) {
			throw new VigenerException('String is not valid for the cipher');
		}
	}


	/**
	 * Sets the key
	 * 
	 * @param null $key
	 * @return Vigener
	 */
	public function setKey($key = null)
	{
		if (!is_null($key) && (is_string($key)) && $key != '') {
			$this->validate($key);
			$this->key = $key;
		}
		return $this;
	}


	/**
	 * Encrypts the string given
	 * 
	 * @param string $value
	 * @return string
	 */
	public function encrypt($value = '')
	{
		if ($value == '') {
			return $value;
		} else {
			$this->validate($value);
		}

		$new_string = array();
		$c = 0;

		for ($i = 0; $i < strlen($value); $i++) {
			$a = $this->use[$value[$i]];
			$b = $this->use[$this->key[$c]];
			$change = $a + $b;
			# if it goes over the count of the array - start over
			if ($change > strlen($this->full_string)) {
				$change = $change - strlen($this->full_string);
			}
			$new_string[] = $this->full_string[$change-1];

			$c++;
			if ($c == strlen($this->key)) {
				$c = 0;
			}
		}
		return join('',$new_string);
	}


	/**
	 * Decrypts the string given
	 * 
	 * @param string $value
	 * @return string
	 */
	public function decrypt($value = '')
	{
		if ($value == '') {
			return $value;
		}

		$new_string = array();
		$c = 0;

		for ($i = 0; $i < strlen($value); $i++) {
			$temp_var = $value[$i];
			$a = $this->use[$temp_var];
			$b = $this->use[$this->key[$c]];
			$change = $a - $b;

			# if it goes over the count of the array - start over
			if ($change < 0) {
				$change = strlen($this->full_string) - abs($change);
			}
			$new_string[] = $this->full_string[$change-1];

			$c++;
			if ($c == strlen($this->key)) {
				$c = 0;
			}
		}
		return join('',$new_string);
	}
}

class VigenerException extends \Exception {}
