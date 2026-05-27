<?php

class Memoria {
    //Atributos
    private string $nome;
    private string $descricao;
    private string $imagem;
    private string $tipo;
    private string $frequencia;
    private string $dataMemoria;

    
    //Métodos
    public function __construct(string $nome , string $descricao , string $imagem, string $tipo , string $frequencia , string $dataMemoria){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->imagem = $imagem;
        $this->tipo = $tipo;
        $this->frequencia = $frequencia;
        $this->dataMemoria = $dataMemoria;
    }

    //GET's & SET's
    
    /**
     * Get the value of nome
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of descricao
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of imagem
     */
    public function getImagem(): string
    {
        return $this->imagem;
    }

    /**
     * Set the value of imagem
     */
    public function setImagem(string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     */
    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of frequencia
     */
    public function getFrequencia(): string
    {
        return $this->frequencia;
    }

    /**
     * Set the value of frequencia
     */
    public function setFrequencia(string $frequencia): self
    {
        $this->frequencia = $frequencia;

        return $this;
    }

    /**
     * Get the value of dataMemoria
     */
    public function getDataMemoria(): string
    {
        return $this->dataMemoria;
    }

    /**
     * Set the value of dataMemoria
     */
    public function setDataMemoria(string $dataMemoria): self
    {
        $this->dataMemoria = $dataMemoria;

        return $this;
    }
}


?>