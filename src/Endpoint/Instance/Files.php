<?php

namespace Opensaucesystems\Lxd\Endpoint\Instance;

use Opensaucesystems\Lxd\Endpoint\AbstractEndpoint;

class Files extends AbstractEndpoint
{
    private $endpoint;

    protected function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Read the contents of a file in a container
     *
     * @param  string $name     Name of container
     * @param  string $filepath Full path to a file within the container
     * @return object
     */
    public function read($name, $filepath)
    {
        $config = [
            "project"=>$this->client->getProject(),
            "path"=>$filepath
        ];

        return $this->get($this->getEndpoint().$name.'/files', $config);
    }

    /**
     * Write to a file in a container
     *
     *
     * @param  string $name     Name of container
     * @param  string $filepath Path to the output file in the container
     * @param  string $data     Data to write to the file
     * @return object
     */
    public function write($name, $filepath, $data, $uid = null, $gid = null, $mode = null, $type = "file")
    {
        $headers = [];

        if (is_numeric($uid)) {
            $headers['X-LXD-uid'] = $uid;
        }

        if (is_numeric($gid)) {
            $headers['X-LXD-gid'] = $gid;
        }

        if (is_numeric($mode)) {
            $headers['X-LXD-mode'] = $mode;
        }

        if (is_string($type)) {
            $headers['X-LXD-type'] = $type;
        }

        $config = [
            "project"=>$this->client->getProject(),
            "path"=>$filepath
        ];

        return $this->post($this->getEndpoint().$name.'/files', $data, $config, $headers);
    }

    /**
     * Delete a file in a container
     *
     * @param  string $name     Name of container
     * @param  string $filepath Full path to a file within the container
     * @return object
     */
    public function remove($name, $filepath)
    {
        $config = [
            "project"=>$this->client->getProject(),
            "path"=>$filepath
        ];
        return $this->delete($this->getEndpoint().$name.'/files', $config);
    }
}
