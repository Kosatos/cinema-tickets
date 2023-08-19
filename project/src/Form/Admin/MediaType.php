<?php

namespace App\Form\Admin;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'by_reference' => false,
                'allow_delete' => true,
                'constraints' => [
                    new Callback([
                        $this,
                        'validate'
                    ])
                ]
            ]);
    }
    public function validate(?UploadedFile $file, ExecutionContextInterface $context): void
    {
        if (!$file) {
            return;
        }

        if (!in_array($file->getMimeType(), ['image/jpg', 'image/jpeg', 'image/png'])) {
            $context->buildViolation(sprintf('%s -- не допустимое расширение, допустимые: jpg, jpeg, png', $file->getMimeType()))
                ->atPath('imageFile')
                ->addViolation();
        }

        $invalidFileNameLength = strlen($file->getClientOriginalName());

        if ($invalidFileNameLength >= 100) {
            $context->buildViolation(sprintf('Длина имени файла %s символа, что превышает допустимое значение в 100 символов!', $invalidFileNameLength))
                ->atPath('imageFile')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
