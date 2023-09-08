<?php

namespace App\Controller\Admin;

use App\Entity\Seat;
use App\Repository\SeatRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;


class SeatCrudController extends AbstractCrudController
{
	public function __construct(private readonly SeatRepository $seatRepository)
	{
	}

	public static function getEntityFqcn(): string
	{
		return Seat::class;
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setEntityLabelInPlural('Места')
			->setEntityLabelInSingular('Место');
	}

	public function configureFields(string $pageName): iterable
	{
		return [
			ArrayField::new('identifier', 'Ряд и Место')
				->setTextAlign('center')
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->setHelp('Идентифицируйте место в формате: <p><strong style="color: #7c2d12">НОМЕР РЯДА, НОМЕР МЕСТА</strong></p>')
				->setFormTypeOptions([
					'constraints' => [
						new Callback([
							$this,
							'validate'
						])
					],
					'error_bubbling' => false,
				])
			,
			BooleanField::new('isVip', 'VIP')
				->onlyOnIndex()
			,
			FormField::addRow(),
			AssociationField::new('hall', 'Зал')
				->setTextAlign('center')
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->setHelp('Выберете номер зала, для которого формируется схема мест.')
		];
	}

	public function validate($identifier, ExecutionContextInterface $context): void
	{
		/**@var Seat $currentSeat */
		$currentSeat = $this->getContext()->getEntity()->getInstance();

		$identifier = array_values($identifier);

		$invalidSeat = array_filter($this->seatRepository->findAll(), fn(Seat $seat) => $currentSeat !== $seat && $seat->getIdentifier()[0] == $identifier[0] && $seat->getIdentifier()[1] == $identifier[1] && $seat->getHall() === $currentSeat->getHall());

		if (count($invalidSeat) > 0) {
			$context->buildViolation('Такое место в этом кинозале уже существует')
				->atPath('identifier')
				->addViolation();
		}
	}
}
