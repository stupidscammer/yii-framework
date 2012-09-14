<?php
/**
 * CDataProvider is a base class that implements the {@link IDataProvider} interface.
 *
 * Derived classes mainly need to implement three methods: {@link fetchData},
 * {@link fetchKeys} and {@link calculateTotalItemCount}.
 *
 * @property string $id The unique ID that uniquely identifies the data provider among all data providers.
 * @property CPagination $pagination The pagination object. If this is false, it means the pagination is disabled.
 * @property CSort $sort The sorting object. If this is false, it means the sorting is disabled.
 * @property array $data The list of data items currently available in this data provider.
 * @property array $keys The list of key values corresponding to {@link data}. Each data item in {@link data}
 * is uniquely identified by the corresponding key value in this array.
 * @property integer $itemCount The number of data items in the current page.
 * @property integer $totalItemCount Total number of possible data items.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web
 * @since 1.1
 */
abstract class CDataProvider extends CComponent implements IDataProvider
{
	private $_id;
	private $_data;
	private $_keys;
	private $_totalItemCount;
	private $_sort;
	private $_pagination;

	/**
	 * Fetches the data from the persistent data storage.
	 * @return array list of data items
	 */
	abstract protected function fetchData();
	/**
	 * Fetches the data item keys from the persistent data storage.
	 * @return array list of data item keys.
	 */
	abstract protected function fetchKeys();
	/**
	 * Calculates the total number of data items.
	 * @return integer the total number of data items.
	 */
	abstract protected function calculateTotalItemCount();

	/**
	 * Returns the ID that uniquely identifies the data provider.
	 * @return string the unique ID that uniquely identifies the data provider among all data providers.
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Sets the provider ID.
	 * @param string $value the unique ID that uniquely identifies the data provider among all data providers.
	 */
	public function setId($value)
	{
		$this->_id=$value;
	}

	/**
	 * Returns the pagination object.
	 * @return mixed the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination()
	{
		if($this->_pagination===null)
		{
			$this->_pagination=new CPagination;
			if(($id=$this->getId())!='')
				$this->_pagination->pageVar=$id.'_page';
		}
		return $this->_pagination;
	}

	/**
	 * Sets the pagination for this data provider.
	 * @param mixed $value the pagination to be used by this data provider. This could be a {@link CPagination} object
	 * or an array used to configure the pagination object. If this is false, it means the pagination should be disabled.
	 */
	public function setPagination($value)
	{
		if(is_array($value) && !isset($value['class']))
			$value['class']='CPagination';

		$this->_pagination=is_array($value) || is_string($value) ? Yii::createComponent($value) : $value;
	}

	/**
	 * Returns the sort object.
	 * @return mixed the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort()
	{
		if($this->_sort===null)
		{
			$this->_sort=new CSort;
			if(($id=$this->getId())!='')
				$this->_sort->sortVar=$id.'_sort';
		}
		return $this->_sort;
	}

	/**
	 * Sets the sorting for this data provider.
	 * @param mixed $value the sorting to be used by this data provider. This could be a {@link CSort} object
	 * or an array used to configure the sorting object. If this is false, it means the sorting should be disabled.
	 */
	public function setSort($value)
	{
		if(is_array($value) && !isset($value['class']))
			$value['class']='CSort';

		$this->_sort=is_array($value) || is_string($value) ? Yii::createComponent($value) : $value;
	}

	/**
	 * Returns the data items currently available.
	 * @param boolean $refresh whether the data should be re-fetched from persistent storage.
	 * @return array the list of data items currently available in this data provider.
	 */
	public function getData($refresh=false)
	{
		if($this->_data===null || $refresh)
			$this->_data=$this->fetchData();
		return $this->_data;
	}

	/**
	 * Sets the data items for this provider.
	 * @param array $value put the data items into this provider.
	 */
	public function setData($value)
	{
		$this->_data=$value;
	}

	/**
	 * Returns the key values associated with the data items.
	 * @param boolean $refresh whether the keys should be re-calculated.
	 * @return array the list of key values corresponding to {@link data}. Each data item in {@link data}
	 * is uniquely identified by the corresponding key value in this array.
	 */
	public function getKeys($refresh=false)
	{
		if($this->_keys===null || $refresh)
			$this->_keys=$this->fetchKeys();
		return $this->_keys;
	}

	/**
	 * Sets the data item keys for this provider.
	 * @param array $value put the data item keys into this provider.
	 */
	public function setKeys($value)
	{
		$this->_keys=$value;
	}

	/**
	 * Returns the number of data items in the current page.
	 * This is equivalent to <code>count($provider->getData())</code>.
	 * When {@link pagination} is set false, this returns the same value as {@link totalItemCount}.
	 * @param boolean $refresh whether the number of data items should be re-calculated.
	 * @return integer the number of data items in the current page.
	 */
	public function getItemCount($refresh=false)
	{
		return count($this->getData($refresh));
	}

	/**
	 * Returns the total number of data items.
	 * When {@link pagination} is set false, this returns the same value as {@link itemCount}.
	 * @param boolean $refresh whether the total number of data items should be re-calculated.
	 * @return integer total number of possible data items.
	 */
	public function getTotalItemCount($refresh=false)
	{
		if($this->_totalItemCount===null || $refresh)
			$this->_totalItemCount=$this->calculateTotalItemCount();
		return $this->_totalItemCount;
	}

	/**
	 * Sets the total number of data items.
	 * This method is provided in case when the total number cannot be determined by {@link calculateTotalItemCount}.
	 * @param integer $value the total number of data items.
	 * @since 1.1.1
	 */
	public function setTotalItemCount($value)
	{
		$this->_totalItemCount=$value;
	}
}
