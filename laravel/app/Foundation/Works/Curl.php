<?php
namespace App\Foundation\Works;

use RuntimeException;

class Curl
{
    protected $curlObject = null;

    protected $allowException = true;

    protected $curlOptions = [
        'RETURNTRANSFER' => true,
        'FAILONERROR' => true,
        'FOLLOWLOCATION' => false,
        'CONNECTTIMEOUT' => '',
        'CONNECTTIMEOUT_MS' => '',
        'TIMEOUT' => 30,
        'TIMEOUT_MS' => '',
        'USERAGENT' => '',
        'URL' => '',
        'POST' => false,
        'HTTPHEADER' => [],
    ];

    protected $packageOptions = [
        'data' => [],
        'asJson' => false,
        'returnAsArray' => false,
        'enableDebug' => false,
        'debugFile' => '',
        'saveFile' => '',
    ];

    public function to($url)
    {
        return $this->withCurlOption('URL', $url);
    }

    public function withException($boolean = true)
    {
        $this->allowException = $boolean;
        return $this;
    }

    public function withTimeout($timeout = 30)
    {
        return $this->withCurlOption('TIMEOUT', $timeout);
    }

    public function withConnectTimeout($timeout = 30)
    {
        return $this->withCurlOption('CONNECTTIMEOUT', $timeout);
    }

    public function withTimeoutMs($timeout = 3000)
    {
        unset($this->curlOptions['TIMEOUT']);
        $this->withCurlOption('NOSIGNAL', 1);
        return $this->withCurlOption('TIMEOUT_MS', $timeout);
    }

    public function withConnectTimeoutMs($timeout = 3000)
    {
        unset($this->curlOptions['CONNECTTIMEOUT']);
        $this->withCurlOption('NOSIGNAL', 1);
        return $this->withCurlOption('CONNECTTIMEOUT_MS', $timeout);
    }

    public function withData($data = array())
    {
        return $this->withPackageOption('data', $data);
    }

    public function asJson($asArray = false)
    {
        return $this->withPackageOption('asJson', true)
            ->withPackageOption('returnAsArray', $asArray);
    }

    public function withOption($key, $value)
    {
        return $this->withCurlOption($key, $value);
    }

    protected function withCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;
        return $this;
    }

    protected function withPackageOption($key, $value)
    {
        $this->packageOptions[$key] = $value;
        return $this;
    }

    public function withHeader($header)
    {
        $this->curlOptions['HTTPHEADER'][] = $header;
        return $this;
    }

    public function withContentType($contentType)
    {
        return $this->withHeader('Content-Type: ' . $contentType)
            ->withHeader('Connection: Keep-Alive');
    }

    public function enableDebug($logFile)
    {
        return $this->withPackageOption('enableDebug', true)
            ->withPackageOption('debugFile', $logFile)
            ->withOption('VERBOSE', true);
    }

    public function get()
    {
        $parameterString = '';
        if (is_array($this->packageOptions['data']) && count($this->packageOptions['data']) != 0) {
            $parameterString = '?' . http_build_query($this->packageOptions['data']);
        }
        $this->curlOptions['URL'] .= $parameterString;
        return $this->send();
    }

    public function post()
    {
        $this->setPostParameters();
        return $this->send();
    }

    public function download($fileName)
    {
        $this->packageOptions['saveFile'] = $fileName;
        return $this->send();
    }

    protected function setPostParameters()
    {
        $this->curlOptions['POST'] = true;
        $parameters = $this->packageOptions['data'];
        if ($this->packageOptions['asJson']) {
            $parameters = json_encode($parameters);
        }
        $this->curlOptions['POSTFIELDS'] = $parameters;
    }

    public function put()
    {
        $this->setPostParameters();
        return $this->withOption('CUSTOMREQUEST', 'PUT')
            ->send();
    }

    public function delete()
    {
        return $this->withOption('CUSTOMREQUEST', 'DELETE')
            ->send();
    }

    protected function send()
    {
        if ($this->packageOptions['asJson']) {
            $this->withHeader('Content-Type: application/json');
        }

        if ($this->packageOptions['enableDebug']) {
            $debugFile = fopen($this->packageOptions['debugFile'], 'w');
            $this->withOption('STDERR', $debugFile);
        }

        $this->curlObject = curl_init();
        $options = $this->forgeOptions();
        curl_setopt_array($this->curlObject, $options);

        $response = curl_exec($this->curlObject);

        if ($response === false) {
            $errno = curl_errno($this->curlObject);
            $msg = sprintf("cURL request failed with error [%s] options %s", curl_strerror($errno), json_encode($this->curlOptions));

            curl_close($this->curlObject);

            if ($this->allowException) {
                throw new RuntimeException($msg, $errno);
            } else {
                write_log("error", $errno . ':' . $msg);
                return false;
            }
        }

        curl_close($this->curlObject);
        if ($this->packageOptions['saveFile']) {
            $file = fopen($this->packageOptions['saveFile'], 'w');
            fwrite($file, $response);
            fclose($file);
        } else if ($this->packageOptions['asJson']) {
            $response = json_decode($response, $this->packageOptions['returnAsArray']);
        }
        if ($this->packageOptions['enableDebug']) {
            fclose($debugFile);
        }

        return $response;
    }

    protected function forgeOptions()
    {
        $results = array();
        foreach ($this->curlOptions as $key => $value) {
            $array_key = constant('CURLOPT_' . $key);
            if ($key == 'POSTFIELDS' && is_array($value)) {
                $results[$array_key] = http_build_query($value, null, '&');
            } else {
                $results[$array_key] = $value;
            }
        }
        return $results;
    }
}
