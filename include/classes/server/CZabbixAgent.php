<?php
/* Used for inicial development: CZabbixServer.php
** Objective: Class for communication with zabbix agent
** Copyright 2014 - Adail Horst - http://spinola.net.br/blog
**
** This file is part of Zabbix-Extras.
** 
** It is not authorized any change that would mask the existence of the plugin. 
** The menu names, logos, authorship and other items identificatory plugin 
** should always be maintained.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
** If not, see http://www.gnu.org/licenses/.
**/

/**
 * A class for interacting with the Zabbix Agent.
 *
 * Class CZabbixAgent
 */
class CZabbixAgent {

	/**
	 * Zabbix agent host name.
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * Zabbix agent port number.
	 *
	 * @var string
	 */
	protected $port;

	/**
	 * Request timeout.
	 *
	 * @var int
	 */
	protected $timeout;

	/**
	 * Maximum response size. If the size of the response exceeds this value, an error will be triggered.
	 *
	 * @var int
	 */
	protected $totalBytesLimit;

	/**
	 * Bite count to read from the response with each iteration.
	 *
	 * @var int
	 */
	protected $readBytesLimit = 8192;

	/**
	 * Zabbix agent socket resource.
	 *
	 * @var resource
	 */
	protected $socket;

	/**
	 * Error message.
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Class constructor.
	 *
	 * @param string $host
	 * @param int $port
	 * @param int $timeout
	 * @param int $totalBytesLimit
	 */
	public function __construct($host, $port, $timeout, $totalBytesLimit) {
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
		$this->totalBytesLimit = $totalBytesLimit;
	}

	/**
	 * Get data from Zabbix Agent
	 *
	 * @param $key
	 *
	 * @return mixed    the output of the script if it has been executed successfully or false otherwise
	 */
	public function getKey($key) {
		return $this->request($key);
	}

	/**
	 * Returns true if the Zabbix Agent port is available for Zabbix Frontend Server.
	 *
	 * @return bool
	 */
	public function isRunning() {
		return (bool) $this->connect();
	}

	/**
	 * Returns the error message.
	 *
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
	/**
	 * Get data from Zabbix Agent
	 *
	 * @param $key
	 *
	 * @return mixed    the output of the script if it has been executed successfully or false otherwise
	 */
	protected function request($key) {
		// connect to the server
		if (!$this->connect()) {
			return false;
		}

		// set timeout
		stream_set_timeout($this->socket, $this->timeout);
		// send the command
		if (fwrite($this->socket, $key) === false) {
			$this->error = _s('Cannot send command, check connection with Zabbix agent "%1$s".', $this->host);

			return false;
		}

		// read the response
		$readBytesLimit = ($this->totalBytesLimit && $this->totalBytesLimit < $this->readBytesLimit)
			? $this->totalBytesLimit
			: $this->readBytesLimit;

		$response = '';
		$now = time();
		$i = 0;
		while (!feof($this->socket)) {
			$i++;
			if ((time() - $now) >= $this->timeout) {
				$this->error = _s('Connection timeout of %1$s seconds exceeded when connecting to Zabbix agent "%2$s".', $this->timeout, $this->host);

				return false;
			}
			elseif ($this->totalBytesLimit && ($i * $readBytesLimit) >= $this->totalBytesLimit) {
				$this->error = _s('Size of the response received from Zabbix agent "%1$s" exceeds the allowed size of %2$s bytes. This value can be increased in the ZBX_SOCKET_BYTES_LIMIT constant in include/defines.inc.php.', $this->host, $this->totalBytesLimit);

				return false;
			}

			if (($out = fread($this->socket, $readBytesLimit)) !== false) {
				$response .= $out;
			}
			else {
				$this->error = _s('Cannot read the response, check connection with the Zabbix agent "%1$s".', $this->host);

				return false;
			}
		}
		fclose($this->socket);

		// check if the response is empty
		if (!strlen($response)) {
			$this->error = _s('Empty response received from Zabbix agent "%1$s".', $this->host);
			return false;
		}
        	return $response;
	}

	/**
	 * Opens a socket to the Zabbix agent. Returns the socket resource if the connection has been established or
	 * false otherwise.
	 *
	 * @return bool|resource
	 */
	protected function connect() {
		if (!$this->socket) {
			if (!$this->host || !$this->port) {
				return false;
			}

			if (!$socket = @fsockopen($this->host, $this->port, $errorCode, $errorMsg, $this->timeout)) {
                            // Todo: need to update this strings...
				switch ($errorMsg) {
					case 'Connection refused':
						$dErrorMsg = _s("Connection to Zabbix agent \"%s\" refused. Possible reasons:\n1. Incorrect agent IP/DNS in the \"zabbix.conf.php\";\n2. Security environment (for example, SELinux) is blocking the connection;\n3. Zabbix agent daemon not running;\n4. Firewall is blocking TCP connection.\n", $this->host);
						break;

					case 'No route to host':
						$dErrorMsg = _s("Zabbix agent \"%s\" can not be reached. Possible reasons:\n1. Incorrect server IP/DNS in the \"zabbix.conf.php\";\n2. Incorrect network configuration.\n", $this->host);
						break;

					case 'Connection timed out':
						$dErrorMsg = _s("Connection to Zabbix agent \"%s\" timed out. Possible reasons:\n1. Incorrect server IP/DNS in the \"zabbix.conf.php\";\n2. Firewall is blocking TCP connection.\n", $this->host);
						break;

					default:
						$dErrorMsg = _s("Connection to Zabbix agent \"%s\" failed. Possible reasons:\n1. Incorrect server IP/DNS in the \"zabbix.conf.php\";\n2. Incorrect DNS server configuration.\n", $this->host);
				}
				$this->error = $dErrorMsg.$errorMsg;
			}

			$this->socket = $socket;
		}

		return $this->socket;
	}

}
