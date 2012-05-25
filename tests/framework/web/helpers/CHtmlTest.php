<?php

class CHtmlTest extends CTestCase
{
    /* HTML characters encode/decode tests */
    
    public static function providerEncodeArray()
    {
        return array(
                array( array('lessThanExpression'=>'4 < 9'), array('lessThanExpression'=>'4 &lt; 9') ),
                array( array(array('lessThanExpression'=>'4 < 9')), array(array('lessThanExpression'=>'4 &lt; 9')) ),
                array( array(array('lessThanExpression'=>'4 < 9'), 'greaterThanExpression'=>'4 > 9'), array(array('lessThanExpression'=>'4 &lt; 9'), 'greaterThanExpression'=>'4 &gt; 9') )
            );
    }
    
    /**
     * @dataProvider providerEncodeArray
     * 
     * @param type $data
     * @param type $assertion 
     */
    public function testEncodeArray($data, $assertion)
    {
        $this->assertEquals($assertion, CHtml::encodeArray($data));
    }
    
    /* Javascript generator tests */

    public static function providerAjax()
    {
        return array(
                array(array("url" => "index"), "jQuery.ajax({'url':'index','cache':false});"),
                array(array("url" => "index", "success" => "function() { this.alert(\"HI\"); }"), "jQuery.ajax({'url':'index','success':function() { this.alert(\"HI\"); },'cache':false});"),
                array(array("async" => true, "success" => "function() { this.alert(\"HI\"); }"), "jQuery.ajax({'async':true,'success':function() { this.alert(\"HI\"); },'url':location.href,'cache':false});"),
                array(array("update" =>"#my-div", "success" => "function() { this.alert(\"HI\"); }"), "jQuery.ajax({'success':function() { this.alert(\"HI\"); },'url':location.href,'cache':false});"),
                array(array("update" =>"#my-div"), "jQuery.ajax({'url':location.href,'cache':false,'success':function(html){jQuery(\"#my-div\").html(html)}});"),
                array(array("replace" =>"#my-div", "success" => "function() { this.alert(\"HI\"); }"), "jQuery.ajax({'success':function() { this.alert(\"HI\"); },'url':location.href,'cache':false});"),
                array(array("replace" =>"#my-div"), "jQuery.ajax({'url':location.href,'cache':false,'success':function(html){jQuery(\"#my-div\").replaceWith(html)}});")
            );
    }
    
    /**
     * @dataProvider providerAjax
     * 
     * @param type $options
     * @param type $assertion 
     */
    public function testAjax($options, $assertion)
    {
        $this->assertEquals($assertion, CHtml::ajax($options));
    }
    
    /* DOM element generated from model attribute tests */
    
    public static function providerActiveDOMElements()
    {
        return array(
                array(new CHtmlTestModel(array('attr1'=>true)), 'attr1', array(), '<input id="ytCHtmlTestModel_attr1" type="hidden" value="0" name="CHtmlTestModel[attr1]" /><input name="CHtmlTestModel[attr1]" id="CHtmlTestModel_attr1" value="1" type="checkbox" />'),
                array(new CHtmlTestModel(array('attr1'=>false)), 'attr1', array(), '<input id="ytCHtmlTestModel_attr1" type="hidden" value="0" name="CHtmlTestModel[attr1]" /><input name="CHtmlTestModel[attr1]" id="CHtmlTestModel_attr1" value="1" type="checkbox" />')
            );
    }
    
    /**
     * @dataProvider providerActiveDOMElements
     *
     * @param string $action
     * @param string $method
     * @param array $htmlOptions
     * @param string $assertion
     */
    public function testActiveCheckbox($model,$attribute,$htmlOptions, $assertion)
    {
        $this->assertEquals($assertion, CHtml::activeCheckBox($model,$attribute,$htmlOptions));
    }
    
    /* Static DOM element generator tests */
    
    public static function providerBeginForm()
    {
        return array(
                array("index", "get", array(), '<form action="index" method="get">'),
                array("index", "post", array(), '<form action="index" method="post">'),
                array("index?myFirstParam=3&mySecondParam=true", "get", array(), 
"<form action=\"index?myFirstParam=3&amp;mySecondParam=true\" method=\"get\">
<div style=\"display:none\"><input type=\"hidden\" value=\"3\" name=\"myFirstParam\" />
<input type=\"hidden\" value=\"true\" name=\"mySecondParam\" /></div>"),
                
            );
    }
    
    /**
     * @dataProvider providerBeginForm
     *
     * @param string $action
     * @param string $method
     * @param array $htmlOptions
     * @param string $assertion
     */
    public function testBeginForm($action, $method, $htmlOptions, $assertion)
    {
        /* TODO - Steven Wexler - 3/5/11 - Mock out static methods in this function when CHtml leverages late static method binding
         * because PHPUnit.  This is only possible Yii supports only >= PHP 5.3   - */
        $this->assertEquals($assertion, CHtml::beginForm($action, $method, $htmlOptions));
    }
    
    public static function providerTextArea()
    {
        return array(
                array("textareaone", '', array(), "<textarea name=\"textareaone\" id=\"textareaone\"></textarea>"),
                array("textareaone", '', array("id"=>"MyAwesomeTextArea", "dog"=>"Lassie", "class"=>"colorful bright"), "<textarea id=\"MyAwesomeTextArea\" dog=\"Lassie\" class=\"colorful bright\" name=\"textareaone\"></textarea>"),
                array("textareaone", '', array("id"=>false), "<textarea name=\"textareaone\"></textarea>"),
            );
    }
    
    /**
     * @dataProvider providerTextArea
     *
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @param string $assertion
     */
    public function testTextArea($name, $value, $htmlOptions, $assertion)
    {
        $this->assertEquals($assertion, CHtml::textArea($name, $value, $htmlOptions));
    }

	public function providerOpenTag()
	{
		return array(
			array('div', array(), '<div>'),
			array('h1', array('id'=>'title', 'class'=>'red bold'), '<h1 id="title" class="red bold">'),
			array('ns:tag', array('attr1'=>'attr1value1 attr1value2'), '<ns:tag attr1="attr1value1 attr1value2">'),
			array('option', array('checked'=>true, 'disabled'=>false, 'defer'=>true), '<option checked="checked" defer="defer">'),
			array('another-tag', array('some-attr'=>'<>/\\<&', 'encode'=>true), '<another-tag some-attr="&lt;&gt;/\&lt;&amp;">'),
			array('tag', array('attr-no-encode'=>'<&', 'encode'=>false), '<tag attr-no-encode="<&">'),
		);
	}

	/**
	 * @dataProvider providerOpenTag
	 *
	 * @param string $tag
	 * @param string $htmlOptions
	 * @param string $assertion
	 */
	public function testOpenTag($tag, $htmlOptions, $assertion)
	{
		$this->assertEquals($assertion, CHtml::openTag($tag, $htmlOptions));
	}

	public function providerCloseTag()
	{
		return array(
			array('div', '</div>'),
			array('h1', '</h1>'),
			array('ns:tag', '</ns:tag>'),
			array('minus-tag', '</minus-tag>'),
		);
	}

    /**
	 * @dataProvider providerCloseTag
	 *
	 * @param string $tag
	 * @param string $assertion
	 */
	public function testCloseTag($tag, $assertion)
	{
		$this->assertEquals($assertion, CHtml::closeTag($tag));
	}

	public function providerCdata()
	{
		return array(
			array('cdata-content', '<![CDATA[cdata-content]]>'),
			array('123321', '<![CDATA[123321]]>'),
		);
	}

	/**
	 * @dataProvider providerCdata
	 *
	 * @param string $data
	 * @param string $assertion
	 */
	public function testCdata($data, $assertion)
	{
		$this->assertEquals($assertion, CHtml::cdata($data));
	}

	public function providerMetaTag()
	{
		return array(
			array('simple-meta-tag', null, null, array(),
				'<meta content="simple-meta-tag" />'),
			array('test-name-attr', 'random-name', null, array(),
				'<meta name="random-name" content="test-name-attr" />'),
			array('text/html; charset=UTF-8', null, 'Content-Type', array(),
				'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'),
			array('test-attrs', null, null, array('xhtml-invalid-attr'=>'attr-value'),
				'<meta xhtml-invalid-attr="attr-value" content="test-attrs" />'),
			array('complex-test', 'testing-name', 'Content-Type', array('attr1'=>'value2'),
				'<meta attr1="value2" name="testing-name" http-equiv="Content-Type" content="complex-test" />'),
		);
	}

	/**
	 * @dataProvider providerMetaTag
	 *
	 * @param string $content
	 * @param string $name
	 * @param string $httpEquiv
	 * @param array $options
	 * @param string $assertion
	 */
	public function testMetaTag($content, $name, $httpEquiv, $options, $assertion)
	{
		$this->assertEquals($assertion, CHtml::metaTag($content, $name, $httpEquiv, $options));
	}

}

/* Helper classes */

class CHtmlTestModel extends CModel
{
    private static $_names=array();
    
    /**
     * @property mixed $attr1
     */
    public $attr1;
    
    /**
     * @property mixed $attr2
     */
    public $attr2;
    
    /**
     * @property mixed $attr3
     */
    public $attr3;
    
    /**
     * @property mixed $attr4
     */
    public $attr4;
    
    public function __constructor(array $properties)
    {
        foreach($properties as $property=>$value)
        {
            if(!property_exists($this, $property))
            {
                throw new Exception("$property is not a property of this class, and I'm not allowing you to add it!");
            }
            $this->{$property} = $value;
        }
    }
    
    /**
	 * Returns the list of attribute names.
	 * @return array list of attribute names. Defaults to all public properties of the class.
	 */
	public function attributeNames()
	{
		$className=get_class($this);
		if(!isset(self::$_names[$className]))
		{
			$class=new ReflectionClass(get_class($this));
			$names=array();
			foreach($class->getProperties() as $property)
			{
				$name=$property->getName();
				if($property->isPublic() && !$property->isStatic())
					$names[]=$name;
			}
			return self::$_names[$className]=$names;
		}
		else
			return self::$_names[$className];
	}

}