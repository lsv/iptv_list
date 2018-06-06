<?php

namespace App\FormModel;

use App\Parser\Parsers\ParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class InputForm
{

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $parser;

    /**
     * @var string|null
     *
     * @Assert\Type("string")
     */
    private $inputData;

    /**
     * @var UploadedFile|null
     *
     * @Assert\File()
     */
    private $uploadedFile;

    /**
     * @var string
     */
    private $data;

    public function getParser(): ?string
    {
        return $this->parser;
    }

    public function getParserObject(): ?ParserInterface
    {
        if (method_exists($this->parser, 'getName')) {
            return new $this->parser;
        }

        return null;
    }

    public function setParser(string $parser): self
    {
        $this->parser = $parser;
        return $this;
    }

    public function getInputData(): ?string
    {
        return $this->inputData;
    }

    public function setInputData(string $inputData): self
    {
        $this->inputData = $inputData;
        return $this;
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(?UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context): void
    {
        if (null === $this->inputData && null === $this->uploadedFile) {
            $context
                ->buildViolation('Either upload a file, or add some data')
                ->addViolation()
            ;
        }
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

}
