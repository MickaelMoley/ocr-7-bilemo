<?php

namespace App\Representation;

use App\Entity\API\APIUser;
use JMS\Serializer\Annotation\Type;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class Customer
{
	/**
	 * @Type(name="array<App\Entity\API\APICustomer>")
	 */
	public $data;

	public $meta;


	public function __construct(Pagerfanta $data, UrlGeneratorInterface $urlGenerator, APIUser $APIUser)
	{
		//Get query result with current filter
		$this->data = $data->getCurrentPageResults();

		$this->addMeta('current_items', count($data->getCurrentPageResults()));// Nb of current items list
		$this->addMeta('total_items', $data->getNbResults()); //Nb of total items
		$this->addMeta('total_pages', $data->getNbPages()); //Nb of total pages

		//Link to first page
		if( $data->getNbPages() >= 1 ){
			$this->addMeta('first_page', $urlGenerator->generate('api_user_customers_collection_get', [
				'id' => $APIUser->getId(),
				'page' => 1,
				'limit' => $data->getMaxPerPage()
			]));
		}
		//Link to last page
		if($data->getNbPages() > 1){
			$this->addMeta('last_page', $urlGenerator->generate('api_user_customers_collection_get', [
				'id' => $APIUser->getId(),
				'page' => $data->getNbPages(),
				'limit' => $data->getMaxPerPage()
			]));
		}

		//Link to current page
		$this->addMeta('current_page', $urlGenerator->generate('api_user_customers_collection_get', [
			'id' => $APIUser->getId(),
			'page' => $data->getCurrentPage(),
			'limit' => $data->getMaxPerPage()
		]));

		//Link to previous page
		//IF paginator have an previous page then we show it
		if($data->hasPreviousPage()){
			//Generate url to access to previous page
			$this->addMeta('previous_page', $urlGenerator->generate('api_user_customers_collection_get', [
				'id' => $APIUser->getId(),
				'page' => $data->getPreviousPage(),
				'limit' => $data->getMaxPerPage()
			]));
		}

		//Link to next page
		//IF paginator have an next page then we show it
		if($data->hasNextPage()){
			//Generate url to access to next page
			$this->addMeta('next_page',
			$urlGenerator->generate('api_user_customers_collection_get', [
				'id' => $APIUser->getId(),
				'page' => $data->getNextPage(),
				'limit' => $data->getMaxPerPage()
			]));
		}
	}

	public function addMeta($name, $value)
	{
		if (isset($this->meta[$name])) {
			throw new \LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
		}

		$this->setMeta($name, $value);
	}

	public function setMeta($name, $value)
	{
		$this->meta[$name] = $value;
	}
}