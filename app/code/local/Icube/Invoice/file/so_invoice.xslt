<?xml version="1.0"?>
<files>
    <file filename="so_%lastincrementid%.xml">
        <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
            <xsl:output method="xml"/>
            <xsl:template match="/">

                <xsl:for-each select="objects/object">
                    <Order_Data_B2C>
                        <b2c_order_detail>
                            <ORDERID><xsl:value-of select="order/increment_id"/></ORDERID>
                            <ORDERDATE><xsl:value-of select="php:functionString('strftime', '%Y%m%d', order/created_at_timestamp)"/></ORDERDATE>
                            <ORDERTYPE>B2C</ORDERTYPE>
                            <CUSTNAME1><xsl:value-of select="shipping/firstname"/></CUSTNAME1>
                            <CUSTNAME2><xsl:value-of select="shipping/lastname"/></CUSTNAME2>
                            <EMAIL><xsl:value-of select="order/customer_email"/></EMAIL>
                            <PHONE><xsl:value-of select="shipping/telephone"/></PHONE>
                            <ADDRESS1>
                                <xsl:value-of select="php:functionString('substr', shipping/street1,0,35)" />
                            </ADDRESS1>
                            <ADDRESS2>
                                <xsl:if test="php:functionString('substr', shipping/street1,35,35)">
                                    <xsl:value-of select="php:functionString('substr', shipping/street1,35,35)" />
                                </xsl:if>
                            </ADDRESS2>
                            <ADDRESS3>
                                <xsl:if test="php:functionString('substr', shipping/street1,70,35)">
                                    <xsl:value-of select="php:functionString('substr', shipping/street1,70,35)" />
                                </xsl:if>
                            </ADDRESS3>
                            <CITY><xsl:value-of select="shipping/kodekecamatan"/></CITY>
                            <!--<PROVINCE><xsl:value-of select="shipping/region"/></PROVINCE>-->
                            <KODEJALUR><xsl:value-of select="shipping/kodejalur"/></KODEJALUR>
                            <COUNTRY><xsl:value-of select="shipping/country_id"/></COUNTRY>
                            <POSTALCODE><xsl:value-of select="shipping/postcode"/></POSTALCODE>
                            <SHIPCOST><xsl:value-of select="php:functionString('number_format', shipping_amount, 0, '.', '')"/></SHIPCOST>
                            <SHIPCOSTGC>
                                <xsl:if test="(order/base_gift_cards_amount &gt; (order/base_subtotal + order/base_tax_amount + order/base_discount_amount)) and (base_shipping_amount != 0)">
                                    <xsl:value-of select="php:functionString('number_format', order/base_gift_cards_amount - (order/base_subtotal + order/base_tax_amount + order/base_discount_amount), 0, '.', '')"/>
                                </xsl:if>
                            </SHIPCOSTGC>
                            <GRANDPRICE><xsl:value-of select="php:functionString('number_format', subtotal + tax_amount + shipping_amount + discount_amount, 0, '.', '')"/></GRANDPRICE>
                            <REMARK><xsl:value-of select="amasty_orderattributes/remark"/></REMARK>
                            <SALESID></SALESID>
                            <CUSTNO></CUSTNO>
                            <SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
                            <SLOC></SLOC>
                            <SHIPPINGPOINT></SHIPPINGPOINT>
                            <REQDLVDATE>
                                <xsl:value-of select="php:functionString('strftime', '%Y%m%d', created_at_timestamp)"/>
                            </REQDLVDATE>
                            <SALESOFFICE></SALESOFFICE>
                            <SALESGROUP></SALESGROUP>
                            <BOOKINGID></BOOKINGID>
                            <PAYERNAME1><xsl:value-of select="amasty_orderattributes/payer_firstname"/></PAYERNAME1>
                            <PAYERNAME2><xsl:value-of select="amasty_orderattributes/payer_lastname"/></PAYERNAME2>
                            <PAYERADDR1><xsl:value-of select="amasty_orderattributes/payer_address1"/></PAYERADDR1>
                            <PAYERADDR2><xsl:value-of select="amasty_orderattributes/payer_address2"/></PAYERADDR2>
                            <PAYERADDR3><xsl:value-of select="amasty_orderattributes/payer_address3"/></PAYERADDR3>
                            <PAYERPOSTCODE><xsl:value-of select="amasty_orderattributes/payer_postcode"/></PAYERPOSTCODE>
                            <PAYERCITY><xsl:value-of select="amasty_orderattributes/payer_city"/></PAYERCITY>
                            <PAYERPROVINCE><xsl:value-of select="amasty_orderattributes/payer_province"/></PAYERPROVINCE>
                            <PAYERNPWP><xsl:value-of select="amasty_orderattributes/payer_npwp"/></PAYERNPWP>
                            <PAYEREMAIL><xsl:value-of select="amasty_orderattributes/payer_email"/></PAYEREMAIL>
                            <INVOICEID><xsl:value-of select="increment_id"/></INVOICEID>
                            <DELIVERYMTD>DC</DELIVERYMTD>
                        </b2c_order_detail>

                        <xsl:for-each select="items/item">
                            <b2c_product_detail>
                                <ID><xsl:value-of select="position()"/></ID>
                                <PRODUCTNO><xsl:value-of select="sku"/></PRODUCTNO>
                                <UNITPRICE>
                                    <xsl:choose>
                                        <xsl:when test="discount_amount != ''">
                                            <xsl:value-of select="php:functionString('number_format', (row_total - discount_amount) div qty, 0, '.', '')"/>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:value-of select="php:functionString('number_format', row_total div qty, 0, '.', '')"/>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </UNITPRICE>
                                <SALESPRICE>
                                    <xsl:choose>
                                        <xsl:when test="row_total=0">
                                            <xsl:text>1</xsl:text>
                                        </xsl:when>
                                        <xsl:when test="discount_amount != ''">
                                            <xsl:choose>
                                                <xsl:when test="tax_amount != ''">
                                                    <xsl:value-of select="php:functionString('number_format', (row_total + tax_amount - discount_amount), 0, '.', '')"/>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:value-of select="php:functionString('number_format', (row_total - discount_amount), 0, '.', '')"/>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:when>
                                        <xsl:when test="tax_amount != ''">
                                            <xsl:value-of select="php:functionString('number_format', (row_total + tax_amount), 0, '.', '')"/>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:value-of select="php:functionString('number_format', (row_total), 0, '.', '')"/>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </SALESPRICE>
                                <QUANTITY><xsl:value-of select="round(qty_ordered)"/></QUANTITY>
                                <UNIT><xsl:value-of select="product_attributes/sales_uom"/></UNIT>
                                <CURRENCY><xsl:value-of select="../../order/base_currency_code"/></CURRENCY>
                                <CONDTYPE></CONDTYPE>
                                <CONSIGNMENT_FLAG>
                                    <xsl:choose>
                                        <xsl:when test="product_attributes/product_source='Konsinyasi DC'">
                                            <xsl:text>1</xsl:text>
                                        </xsl:when>
                                        <xsl:when test="product_attributes/product_source='Konsinyasi Vendor'">
                                            <xsl:text>1</xsl:text>
                                        </xsl:when>
                                    </xsl:choose>
                                </CONSIGNMENT_FLAG>
                                <WARRANTY_CODE><xsl:value-of select="product_attributes/warranty_code"/></WARRANTY_CODE>
                                <SITE>
                                    <xsl:choose>
                                        <xsl:when test="product_attributes/warehouse_selection='H001'">
                                            <xsl:text>O002</xsl:text>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:text>O001</xsl:text>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </SITE>
                            </b2c_product_detail>


                            <xsl:if test="not(../../order/gift_cards='a:0:{}')">
                                <b2c_product_detail>
                                    <ID><xsl:value-of select="position()"/></ID>
                                    <PRODUCTNO><xsl:value-of select="sku"/></PRODUCTNO>
                                    <UNITPRICE>
                                        <xsl:choose>
                                            <xsl:when test="base_discount_amount != ''">
                                                <xsl:choose>
                                                    <xsl:when test="../../order/base_gift_cards_amount &gt; (../../order/base_subtotal + ../../order/base_discount_amount)">
                                                        <xsl:value-of select="php:functionString('number_format', (base_row_total - base_discount_amount) div qty, 0, '.', '')"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <xsl:value-of select="php:functionString('number_format', (../../order/base_gift_cards_amount * (base_row_total - base_discount_amount) div (../../order/base_subtotal + ../../order/base_discount_amount)) div qty, 0, '.', '')"/>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:choose>
                                                    <xsl:when test="../../order/base_gift_cards_amount &gt; (../../order/base_subtotal + ../../order/base_discount_amount)">
                                                        <xsl:value-of select="php:functionString('number_format', base_row_total div qty, 0, '.', '')"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <xsl:value-of select="php:functionString('number_format', (../../order/base_gift_cards_amount * base_row_total div (../../order/base_subtotal + ../../order/base_discount_amount)) div qty, 0, '.', '')"/>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </UNITPRICE>
                                    <SALESPRICE>
                                        <xsl:choose>
                                            <xsl:when test="base_discount_amount != ''">
                                                <xsl:choose>
                                                    <xsl:when test="../../order/base_gift_cards_amount &gt; (../../order/base_subtotal + ../../order/base_discount_amount)">
                                                        <xsl:value-of select="php:functionString('number_format', (base_row_total - base_discount_amount), 0, '.', '')"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <xsl:value-of select="php:functionString('number_format', ../../order/base_gift_cards_amount * (base_row_total - base_discount_amount) div (../../order/base_subtotal + ../../order/base_discount_amount), 0, '.', '')"/>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:choose>
                                                    <xsl:when test="../../order/base_gift_cards_amount &gt; (../../order/base_subtotal + ../../order/base_discount_amount)">
                                                        <xsl:value-of select="php:functionString('number_format', base_row_total, 0, '.', '')"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <xsl:value-of select="php:functionString('number_format', ../../order/base_gift_cards_amount * base_row_total div (../../order/base_subtotal + ../../order/base_discount_amount), 0, '.', '')"/>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </SALESPRICE>
                                    <QUANTITY><xsl:value-of select="round(qty_ordered)"/></QUANTITY>
                                    <UNIT><xsl:value-of select="product_attributes/sales_uom"/></UNIT>
                                    <CURRENCY><xsl:value-of select="../../order/base_currency_code"/></CURRENCY>
                                    <CONDTYPE>ZV04</CONDTYPE>
                                    <CONSIGNMENT_FLAG>
                                        <xsl:choose>
                                            <xsl:when test="product_attributes/product_source='Konsinyasi DC'">
                                                <xsl:text>1</xsl:text>
                                            </xsl:when>
                                            <xsl:when test="product_attributes/product_source='Konsinyasi Vendor'">
                                                <xsl:text>1</xsl:text>
                                            </xsl:when>
                                        </xsl:choose>
                                    </CONSIGNMENT_FLAG>
                                    <WARRANTY_CODE><xsl:value-of select="product_attributes/warranty_code"/></WARRANTY_CODE>
                                    <SITE>
                                        <xsl:choose>
                                            <xsl:when test="product_attributes/warehouse_selection='H001'">
                                                <xsl:text>O002</xsl:text>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:text>O001</xsl:text>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </SITE>
                                </b2c_product_detail>


                            </xsl:if>


                        </xsl:for-each>
                    </Order_Data_B2C>
                </xsl:for-each>

            </xsl:template>
        </xsl:stylesheet>
    </file>
</files>