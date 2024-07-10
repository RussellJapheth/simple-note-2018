<?php

class Notes
{
    protected $notes = __DIR__.'/../notes.ser';
    protected $data = [];
    public function __construct()
    {
        @touch($this->notes);
        $this->data = @unserialize(@file_get_contents($this->notes));

        if ($this->data === false) {
            $this->create_blank();
            $this->data = @unserialize(@file_get_contents($this->notes));
        }
    }
    public function create_blank()
    {
        if (!file_put_contents($this->notes, serialize([]))) {
            ob_end_clean();
            echo "Unable to read notes! :( ".PHP_EOL;
            echo "Check that you have write permission to the application directory! ".PHP_EOL;
            exit;
        }
        $cd = [
            'title' => 'hello world',
            'contents' => '######hello world'.PHP_EOL.'```php'.PHP_EOL.'echo ("hello world!");'.PHP_EOL,
            'owner' => 'migo hogwort',
            'tags' => ['hello world', 'sample'],
            'ctime' => time(),
            'mtime' => time(),
            'atime' => time(),
        ];
        $this->data[uniqid()] = $cd;
        $this->save();
    }
    public function save()
    {
        return @file_put_contents($this->notes, serialize($this->data));
    }
    public function create($data = [])
    {
        $cd = [
            'title' => htmlentities($data['title']),
            'contents' => $data['contents'],
            'owner' => htmlentities($data['owner']),
            'tags' => explode(',', $data['tags']),
            'ctime' => time(),
            'mtime' => time(),
            'atime' => time(),
        ];
        
        return $this->data[uniqid()] = $cd;
    }
    public function read($id = null)
    {
        if ($id === null) {
            return $this->data;
        }
        $ret = (array_key_exists($id, $this->data)) ? $this->data[$id] : [];
        $ret['id'] = $id;
        return $ret;
    }
    public function update($id = '')
    {
    }
    public function delete($id = '')
    {
        if (array_key_exists($id, $this->data)) {
            unset($this->data[$id]);
        }
    }
}
