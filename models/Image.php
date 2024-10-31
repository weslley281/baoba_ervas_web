<?php
class Image
{
    private string $name;
    private string $path;
    private string $dataMudanca;
    private string $dataCriacao;

    public function __construct($name, $path, $dataMudanca)
    {
        $this->name = $name;
        $this->path = $path;
        $this->dataMudanca = $dataMudanca;
        $this->dataCriacao = date("Y-m-d");
    }

    public function getname()
    {
        return $this->name;
    }

    public function setname($name)
    {
        $this->name = $name;
    }

    public function getpath()
    {
        return $this->path;
    }

    public function setpath($path)
    {
        $this->path = $path;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }
}