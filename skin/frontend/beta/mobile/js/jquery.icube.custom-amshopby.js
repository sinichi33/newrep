/*
    Jquery Widget for Custom Amasty Layered Nav

    Description:
    - This widget needs following steps to do before to make it works:
        1. add these codes to /catalog/product/list.phtml:
            - hidden elemnt with category url info:
                <div id="category-url" class="no-display">
                    <?php echo $_category->getUrl() ?>
                </div> 
        2. update js/amasty/amshopby/amshopby-jquery.js
*/

(function ($) {
    'use strict';
    $.widget('icube.catalog_filter', {

        vars: {
            category_url: '',
            cur_params: [],
            added_params: [],
            deleted_params: [],
            link_params: [],
            price_params: [],
            url_type: 3,            // 1 = with GET Parameters, 2 = Long With URL Key, 3 = Short without URL key
            enable_log: true
        },

        _create: function () {
            /* custom filter init */
            this.initCustomFilter();
        },

        /* -------------------------------------------------------- */
        /* Helper Functions
        /* -------------------------------------------------------- */

        _log: function(msg) {
            if (this.vars.enable_log) {
                console.log(msg);
            };
        },

        _URLToArray:function(url) {
            var request = [];
            var pairs = url.substring(url.indexOf('?') + 1).split('&');
            for (var i = 0; i < pairs.length; i++) {
                if(!pairs[i])
                    continue;
                var pair = pairs[i].split('=');
                var values = decodeURIComponent(pair[1]).split(',');
                var item = [];
                // request[decodeURIComponent(pair[0])] = values;
                item.filtername = decodeURIComponent(pair[0]);
                item.filtervalue = values;
                request.push(item);
             }
             return request;
        },

        _checkValIsIn: function(object, search) {
            var result = [];
            $.each(object, function(i, v) {
                if (v.filtername == search.filtername) {
                    var index_val = v.filtervalue.indexOf(search.filtervalue[0]);
                    if( index_val > -1 ) {
                        result['index_filtervalue'] = index_val;
                        result['index_filter'] = i;
                    }
                    return false;   //break
                };
            });

            return result;
        },

        /* -------------------------------------------------------- */
        /* Custom Layered Nav (cln)
        /* -------------------------------------------------------- */

        initCustomFilter: function() {

            /* Define URL type for Catalog page */
            if ($('body.catalog-category-view').length) {
                this.vars.url_type = 3;
            }
            /* Define URL type for Search Result page */
            if ($('body.catalogsearch-result-index').length) {
                this.vars.url_type = 1;
            }
            /* Define URL type for Splash page */
            if ($('body.attributesplash-page-view').length) {
                this.vars.url_type = 1;
            }

            /* do nothing for url_type 2 */
            if (this.vars.url_type != 2) {
                /* Fix class link of color filter 
                 * There is something wrong with color filter link so it generate broken class "-selected" 
                 * insetad of "amshopby-attr-selected".
                 */
                this.clnFixColorFilterLinkClass();
                            
                /* initiate needed variables */
                this.clnInitVars();

                /* filter link on click */
                this.clnClickFilterLink();

                /* View All filter option */
                // this.clnCheckAllToggleOpt();

                /* Custom price filter */
                this.clnPriceFilter();

                /* Done button */
                this.clnDoneBtn();
            };
        },

        /* Fix class link of color filter */
        clnFixColorFilterLinkClass: function() {
            $('.block-layered-nav #narrow-by-list dd.color a').each(function() {
                if ($(this).hasClass('-selected')) {
                    $(this).removeClass('-selected');
                    $(this).addClass('amshopby-attr-selected');
                }
            });
        },

        /* initiate vars */
        clnInitVars: function() {
            var _this = this;

            // category var is gotten from $('#category-url') which is invisible element
            var category_url        = $.trim($('#category-url').text()).replace(/\/?$/, '/');       // --> add "/" at the end if doesn't exist
            this.vars.category_url  = category_url.replace('.html','');                             // --> remove '.html'

            if (_this.vars.url_type == 1) {
                this.vars.cur_params = _this._URLToArray(document.URL);
            }

            if (_this.vars.url_type == 3) {

                // current params
                var browser_url_no_last_splash  = document.URL.replace(/\/$/, "");                      // --> remove "/" at the end if exists
                browser_url_no_last_splash      = browser_url_no_last_splash.replace('.html', "");      // --> remove '.html'
                var category_url_no_last_splash = this.vars.category_url.replace(/\/$/, "");            // --> remove "/" at the end if exists
                category_url_no_last_splash     = category_url_no_last_splash.replace('.html', "");     // --> remove '.html'
                var cur_params = browser_url_no_last_splash.replace(category_url_no_last_splash,'');
                cur_params = cur_params.replace(/^\/|\/$/g, '');                                        // --> remove "/" at the first and the last exists
                cur_params = cur_params.split("-");                                                     // --> split into array by "-"

                var _cur_params = [];
                var price_param = '';
                $.each(cur_params, function(i, v) {
                    // price will have special treatment to put it into cur_params[]
                    // because its format which is contain "-" (the same separator with cur_param's)
                    if (v != 'price' && !$.isNumeric(v)) {
                        _cur_params.push(v);
                    }else{
                        price_param += v + '-';
                    }
                });
                if (price_param != '') {
                    price_param = price_param.substring(0, price_param.length - 1);
                    _cur_params.push(price_param);
                };
                this.vars.cur_params = _cur_params;
            }

            // --- debug
            this._log('### Initiate ###');
            this._log('category_url: ' + this.vars.category_url);
            this._log('cur_params[]: ');
            this._log(this.vars.cur_params);
            this._log('link_params[]: ');
            this._log(this.vars.link_params);
            this._log('added_params[]: ');
            this._log(this.vars.added_params);
            this._log('deleted_params[]: ');
            this._log(this.vars.deleted_params);
            this._log('price_params[]: ');
            this._log(this.vars.price_params);
            this._log('----------------------------');
        },

        /* filter link on click */
        clnClickFilterLink: function() {
            var _this = this;

            $('.block-layered-nav #narrow-by-list dd:not(.price,.not-attr) a').click(function(e) {
                var link = $(this);
                e.preventDefault();
                
                // add/remove checked indicator
                if (link.hasClass('amshopby-attr-selected')) {
                    _this.clnUncheck(link);
                    _this.clnDelFilter(link);
                }else{
                    _this.clnCheck(link);
                    _this.clnAddFilter(link);
                }
            });
        },

        clnCheck: function(link) {
            link.addClass('amshopby-attr-selected');
        },

        clnUncheck: function(link) {
            link.removeClass('amshopby-attr-selected');
        },

        clnAddFilter: function(link) {
            var _this = this;

            /* get link params */
            this.clnGetLinkParams(link);

            /* The process of adding param */
            if (_this.vars.url_type == 1) {
                $.each(_this.vars.link_params, function(i, param) {
                    // /* if param is in deleted_params[], delete it from there */
                    // var deleted_val = _this.clnGetDeletedValueFromLinkParams();
                    // if(_this.vars.deleted_params.length > 0) {
                    //     var checkVal = _this._checkValIsIn(_this.vars.deleted_params, deleted_val);
                    //     if (checkVal) {
                    //         if( checkVal.index_filtervalue > -1 ) {
                    //             _this.vars.deleted_params[checkVal.index_filter]['filtervalue'].splice(checkVal.index_filtervalue, 1);

                    //             // remove filter if has no value anymore
                    //             if (_this.vars.deleted_params[checkVal.index_filter]['filtervalue'].length <= 0) {
                    //                 _this.vars.deleted_params.splice(checkVal.index_filter, 1);
                    //             };
                    //         }
                    //     }
                    // }

                    /* if param is not in cur_params[] and in added_params[] yet, add it into added_params[] */
                    $.each(param.filtervalue, function(i_par, v_par) {
                        var is_in_cur_params    = false;
                        var is_in_added_params  = false;
                        $.each(_this.vars.cur_params, function(i_cur, v_cur) {
                            if (param.filtername == v_cur.filtername) {
                                var index = v_cur.filtervalue.indexOf(v_par);
                                if (index > -1) {
                                    is_in_cur_params = true;
                                    return false
                                };
                            }
                        });
                        $.each(_this.vars.added_params, function(i_add, v_add) {
                            if (param.filtername == v_add.filtername) {
                                var index = v_add.filtervalue.indexOf(v_par);
                                if (index > -1) {
                                    is_in_added_params = true;
                                    return false
                                };
                            }
                        });

                        if( !is_in_cur_params && !is_in_added_params ) {
                            /* add param to added_params[] */
                            if(_this.vars.added_params.length == 0) {
                                var new_item = [];
                                new_item['filtername'] = param.filtername;
                                new_item['filtervalue'] = [v_par];
                                _this.vars.added_params.push(new_item);
                            }else{
                                var filternamefound = false;
                                $.each(_this.vars.added_params, function(i, v) {
                                    if (v.filtername == param.filtername) {
                                        _this.vars.added_params[i].filtervalue.push(v_par);
                                        filternamefound = true;
                                        return false;   //break
                                    };
                                });

                                if (!filternamefound) {
                                    var new_item = [];
                                    new_item['filtername'] = param.filtername;
                                    new_item['filtervalue'] = [v_par];
                                    _this.vars.added_params.push(new_item);
                                };
                            }

                            /* add attribute data-value to link */
                            link.attr('data-val', param.filtername.replace(' ','-') + '-' + v_par);

                            /* mark link as custom filter added */
                            link.addClass('cln-selected');
                        }
                    });
                })
            }
            if (_this.vars.url_type == 3) {
                $.each(_this.vars.link_params, function(i, param) {

                    /* if param is in deleted_params[], delete it from there */
                    var deleted_val = _this.clnGetDeletedValueFromLinkParams();
                    var index_del = _this.vars.deleted_params.indexOf(deleted_val);
                    if( index_del > -1 ) {
                        _this.vars.deleted_params.splice(index_del, 1);
                    }

                    /* if param is not in cur_params[] and in added_params[] yet, add it to added_params[] */
                    var index_cur_params = _this.vars.cur_params.indexOf(param);
                    var index_added_params = _this.vars.added_params.indexOf(param);
                    if( index_cur_params < 0 && index_added_params < 0 ) {
                        /* add param to added_params[] */
                        _this.vars.added_params.push(param);

                        /* add attribute data-value to link */
                        link.attr('data-val', param)

                        /* mark link as custom filter added */
                        link.addClass('cln-selected');
                    }
                });
            }

            // ----- debug 
            this._log('### Add Filter ###');
            this._log('cur_params[]: ');
            this._log(this.vars.cur_params);
            this._log('link_params[]: ');
            this._log(this.vars.link_params);
            this._log('added_params[]: ');
            this._log(this.vars.added_params);
            this._log('deleted_params[]: ');
            this._log(this.vars.deleted_params);
            this._log('price_params[]: ');
            this._log(this.vars.price_params);
            this._log('----------------------------');
        },

        clnDelFilter: function(link) {
            var _this = this;
            var is_custom_added = link.hasClass('cln-selected');

            /* if not custom filter added (cln) */
            if (!is_custom_added) {
                
                this._log('### Delete Filter not cln ###');
                
                /* get link params */
                this.clnGetLinkParams(link);

                /* get param to deleted_params[] */
                var deleted_val = _this.clnGetDeletedValueFromLinkParams();
                if (_this.vars.url_type == 1) {
                    if(_this.vars.deleted_params.length == 0) {
                        _this.vars.deleted_params.push(deleted_val);
                    }else{
                        $.each(_this.vars.deleted_params, function(i, v) {
                            if (v.filtername == deleted_val.filtername) {
                                _this.vars.deleted_params[i].filtervalue.push(deleted_val.filtervalue[0]);
                                return false;   //break
                            };

                            if (i+1 == _this.vars.deleted_params.length) {
                                _this.vars.deleted_params.push(deleted_val);
                            };
                        });
                    }
                }
                if (_this.vars.url_type == 3) {
                    _this.vars.deleted_params.push(deleted_val);
                }
            
            }else{ /* if custom filter added (cln) */
                
                this._log('### Delete Filter cln ###');

                /* get deleted value from attr data-value */
                var deleted_val = link[0].dataset.val;
                
                /* delete param from added_params[] */
                if (_this.vars.url_type == 1) {
                    var arr_deleted_val = deleted_val.split('-');
                    var deleted_val_for_seach = [];
                    deleted_val_for_seach['filtername'] = arr_deleted_val[0];
                    deleted_val_for_seach['filtervalue'] = [arr_deleted_val[1]];

                    var checkVal = _this._checkValIsIn(_this.vars.added_params, deleted_val_for_seach);

                    if (checkVal.index_filtervalue > -1) {
                        _this.vars.added_params[checkVal.index_filter]['filtervalue'].splice(checkVal.index_filtervalue, 1);

                        // remove filter if has no value anymore
                        if (_this.vars.added_params[checkVal.index_filter]['filtervalue'].length <= 0) {
                            _this.vars.added_params.splice(checkVal.index_filter, 1);
                        };
                    };

                }
                if (_this.vars.url_type == 3) {
                    var index = _this.vars.added_params.indexOf(deleted_val);
                    if( index > -1 ) {
                        _this.vars.added_params.splice(index, 1);
                    }

                    /* remove data-value */
                    link.removeAttr('data-val');
                };

                /* remove custom filter added mark */
                link.removeClass('cln-selected');
            }

            // ----- debug 
            this._log('cur_params[]: ');
            this._log(this.vars.cur_params);
            this._log('link_params[]: ');
            this._log(this.vars.link_params);
            this._log('added_params[]: ');
            this._log(this.vars.added_params);
            this._log('deleted_params[]: ');
            this._log(this.vars.deleted_params);
            this._log('price_params[]: ');
            this._log(this.vars.price_params);
            this._log('----------------------------');
        },

        clnGetLinkParams: function(link) {
            var _this = this;

            if (_this.vars.url_type == 1) {
                this.vars.link_params = _this._URLToArray(link.attr('href'));
            }

            if (_this.vars.url_type == 3) {
                var link_href_no_last_splash    = link.attr('href').replace(/\/$/, "");                 // --> remove "/" at the end if exists; 
                link_href_no_last_splash        = link_href_no_last_splash.replace('.html','');         // --> remove '.html'
                var category_url_no_last_splash = this.vars.category_url.replace(/\/$/, "");            // --> remove "/" at the end if exists
                category_url_no_last_splash     = category_url_no_last_splash.replace('.html','');      // --> remove '.html'
                var link_params = link_href_no_last_splash.replace(category_url_no_last_splash,'');
                link_params = link_params.replace(/^\/|\/$/g, '');                                      // --> remove "/" at the first and the last exists
                link_params = link_params.split("-");

                var _link_params = [];
                var price_param = '';
                $.each(link_params, function(i, v) {
                    // price will have special treatment
                    // because its format which is contain "-" (the same separator with cur_param's)
                    if (v != 'price' && !$.isNumeric(v)) {
                        _link_params.push(v);
                    }else{
                        price_param += v + '-';
                    }
                });
                if (price_param != '') {
                    price_param = price_param.substring(0, price_param.length - 1);
                    _link_params.push(price_param);
                };
                this.vars.link_params = _link_params;
            }
        },

        clnGetDeletedValueFromLinkParams: function() {
            var _this = this;

            // note: the deleted value is value which is listed in cur_params[], but not in link_params[]
            var deleted_val = [];
            var cur_params = _this.vars.cur_params;
            var link_params = _this.vars.link_params;

            if (_this.vars.url_type == 1) {
                $.each(cur_params, function(cur_param_i, cur_param_v) {
                    var filternamefound = false;
                    $.each(link_params, function(link_param_i, link_param_v) {
                        if (cur_param_v.filtername == link_param_v.filtername) {   // if filter name exists within link_params
                            filternamefound = true;
                            $.each(cur_param_v.filtervalue, function(cur_param_v_i, cur_param_v_v) {
                                if (link_param_v.filtervalue.indexOf(cur_param_v_v) < 0) {
                                    var deleted = [];
                                    deleted.filtername = cur_param_v.filtername;
                                    deleted.filtervalue = [];
                                    deleted.filtervalue.push(cur_param_v_v);
                                    deleted_val.push(deleted);
                                }
                            });
                        }
                    });

                    if (!filternamefound) {
                        var deleted = [];
                        deleted.filtername = cur_param_v.filtername;
                        deleted.filtervalue = [];
                        deleted.filtervalue.push(cur_param_v.filtervalue[0]);
                        deleted_val.push(deleted)
                    };
                });

                return deleted_val[0];
            }

            if (_this.vars.url_type == 3) {
                $.grep(cur_params, function(el) {
                    if ($.inArray(el, link_params) == -1) deleted_val.push(el);
                });
                
                return deleted_val[0];
            };

        },

        /* View All filter option */
        clnCheckAllToggleOpt: function() {
            var _this = this;

            // add "view all" option to all filters except price
            $('.block-layered-nav #narrow-by-list dd:not(.price):not(.category)').each(function() {
                var link = $('<div class="check-all-wrapper"><a href="javascript:void(0)" class="check-all">View All</a></div>');
                $(this).prepend(link);
                
                link.find('.check-all').click(function() {

                    // set/unset it as active
                    if ($(this).hasClass('amshopby-attr-selected')) {
                        _this.clnUncheck($(this));

                        // unCheck all options
                        $(this).parent().parent().find('a').each(function() {
                            _this.clnUncheck($(this));
                            
                            if (!$(this).hasClass('check-all')) {
                                _this.clnDelFilter($(this));
                            }
                        });
                    }else{
                        _this.clnCheck($(this));

                        // check all options
                        $(this).parent().parent().find('a').each(function() {
                            _this.clnCheck($(this));
                            
                            if (!$(this).hasClass('check-all')) {
                                _this.clnAddFilter($(this));
                            };
                        });
                    }                            
                });
            });
        },

        clnPriceFilter: function() {
            var _this = this;
            $(".amshopby-slider-ui").on('click', function() {
                _this.clnUpdatePriceParam($('#amshopby-price-from-slider').text(), $('#amshopby-price-to-slider').text());
            });
            $('#amshopby-price-from').blur(function() {
                _this.clnUpdatePriceParam($('#amshopby-price-from').val(), $('#amshopby-price-to').val());
                _this.clnRefreshSliderWitdh($('#amshopby-price-from').val(), $('#amshopby-price-to').val());
                $('#amshopby-price-from-slider').text($('#amshopby-price-from').val());
            });
            $('#amshopby-price-to').blur(function() {
                _this.clnUpdatePriceParam($('#amshopby-price-from').val(), $('#amshopby-price-to').val());
                _this.clnRefreshSliderWitdh($('#amshopby-price-from').val(), $('#amshopby-price-to').val());
                $('#amshopby-price-to-slider').text($('#amshopby-price-to').val());
            });

            // hide submit btn of price form
            $('#amshopby-price-btn').hide();
        },

        clnRefreshSliderWitdh: function(from, to) {
            $('.amshopby-slider-ui-param').each(function () {
                var param = $('.amshopby-slider-ui-param').val().split(',');
                var maxValue = param[2];
                
                var sliderHandleLeft    = $('#amshopby-price-ui .ui-slider-handle:first');
                var sliderHandleRight   = $('#amshopby-price-ui .ui-slider-handle:last');
                var handleBar           = $('#amshopby-price-ui .ui-slider-range');

                var handleLeftPos       = (from / maxValue) * 100;
                var handleRightPos      = (to / maxValue) * 100;
                var handleBarWidth      = handleRightPos - handleLeftPos;

                sliderHandleLeft.css('left', handleLeftPos + '%');
                sliderHandleRight.css('left', handleRightPos + '%');
                handleBar.css({'left': handleLeftPos + '%', 'width': handleBarWidth + '%'});
            });
        },

        clnUpdatePriceParam: function(from,to) {
            this.vars.price_params = [from,to];

            // ----- debug 
            this._log('### Filter price has changed ###');
            this._log('price_params[]: ' + this.vars.price_params);
            this._log('----------------------------');
        },

        clnDoneBtn: function() {
            var _this = this;
            var btn1 = $('<a href="javascript:void(0)" class="btn-done button">Refine</a>');
            var btn2 = $('<a href="javascript:void(0)" class="btn-done button">Refine</a>');
            $('.block-layered-nav .block-title').append(btn1);
            $('.block-layered-nav .block-content').append(btn2);

            btn1.click(function() {
                var new_url = _this.generateNewUrl();
                window.location.href = new_url;
            });

            btn2.click(function() {
                var new_url = _this.generateNewUrl();
                window.location.href = new_url;
            });
        },

        generateNewUrl: function() {
            var _this = this;
            var new_url_params = '?';
            var result1 = [];
            var _cur_params = this.vars.cur_params;
            var _added_params = this.vars.added_params;
            var _deleted_params = this.vars.deleted_params;

            if (_this.vars.url_type == 1) {
                result1 = _cur_params;

                // merge cur_params[] with added_params[]
                $.each(_added_params, function(i, v) {
                    var filternamefound = false;
                    $.each(result1, function(ii, vv) {
                        if (v.filtername == vv.filtername) {
                            $.each(v.filtervalue, function(iii, vvv) {
                                var index = vv.filtervalue.indexOf(vvv);
                                if (index < 0) {
                                    result1[ii]['filtervalue'].push(vvv);
                                };
                            });

                            filternamefound = true;
                            return false;   // break
                        };
                    })

                    if (!filternamefound) {
                        var newfilter = [];
                        newfilter.filtername = v.filtername;
                        newfilter.filtervalue = v.filtervalue;
                        result1.push(newfilter);
                    };
                });

                // merge cur_params[] with deleted_params[]
                var result2 = result1;
                if (_deleted_params.length > 0) {
                    $.each(_deleted_params, function(i, v) {
                        $.each(result2, function(ii, vv) {
                            if (typeof vv != 'undefined') {
                                if (v.filtername == vv.filtername) {
                                    $.each(v.filtervalue, function(iii, vvv) {
                                        var index = vv.filtervalue.indexOf(vvv);
                                        if (index > -1) {
                                            result2[ii]['filtervalue'].splice(index, 1);

                                            // remove filter if has no value anymore
                                            if (result2[ii]['filtervalue'].length <= 0) {
                                                result2.splice(ii, 1);
                                            };
                                        };
                                    });
                                };
                            };
                        });
                    });
                };


                // merge cur_params[] with price_params[]
                var result3 = [];
                if ( this.vars.price_params.length > 0) {
                    // dont include price filter to result3
                    $.each(result2, function(i, v) {
                        if(v.filtername != 'price') {
                            result3.push(v);
                        }
                    });
                    var price_param_from    = this.vars.price_params[0];
                    var price_param_to      = this.vars.price_params[1];
                    var price_param         = [];
                    price_param['filtername'] = 'price';
                    var price_param_val = '';
                    if (price_param_to < price_param_from) {
                        price_param_val += price_param_to + '-' + price_param_from;
                    }else{
                        price_param_val += price_param_from + '-' + price_param_to;
                    }
                    price_param['filtervalue'] = [price_param_val];
                    result3.push(price_param);
                    
                    // generate new uri params
                    $.each(result3, function(i, v) {
                        if (i != 0) {new_url_params += '&'};
                        new_url_params += encodeURIComponent(v.filtername) + '=' + encodeURIComponent(v.filtervalue.join(','));
                    });

                }else{
                    // generate new uri params
                    $.each(result2, function(i, v) {
                        if (i != 0) {new_url_params += '&'};
                        new_url_params += encodeURIComponent(v.filtername) + '=' + encodeURIComponent(v.filtervalue.join(','));
                    });
                }

                // add '.html' it if exists
                var new_url  = '';
                if (document.URL.indexOf('.html') >= 0) {
                    new_url  = this.vars.category_url.replace(/\/$/, "");      // --> remove "/" at the end if exists
                    new_url += '.html';
                }

                new_url += new_url_params;


                // ----- debug
                this._log('### Generate new link ###');
                this._log('category_url: ' + this.vars.category_url);
                this._log('cur_params[]: ');
                this._log(this.vars.cur_params);
                this._log('link_params[]: ');
                this._log(this.vars.link_params);
                this._log('added_params[]: ');
                this._log(this.vars.added_params);
                this._log('deleted_params[]: ');
                this._log(this.vars.deleted_params);
                this._log('price_params[]: ');
                this._log(this.vars.price_params);
                this._log('merge cur_params[] with added_params[]: ')
                this._log(result1);
                this._log('merge cur_params[] with deleted_params[]: ');
                this._log(result2);
                this._log('new_url_params: ' +new_url_params);
                this._log('new_url: ' +new_url);
                this._log('----------------------------');

            };

            if (_this.vars.url_type == 3) {

                // merge cur_params[] with added_params[]
                result1 = _cur_params.concat(_added_params);
                // remove empty item of array
                result1 = result1.filter(function(v){return v!==''});


                // merge cur_params[] with deleted_params[]
                var result2 = result1;
                if (_deleted_params.length > 0) {
                    $.each(_deleted_params, function(i, deleted_val) {
                        var index = result2.indexOf(deleted_val);
                        if( index > -1 ) {
                            result2.splice(index, 1);
                        }
                    });
                };
                // remove empty item of array
                result2 = result2.filter(function(v){return v!==''});


                // if has price params, remove it
                var result3 = [];
                if ( this.vars.price_params.length > 0) {
                    // dont include filter which is numeric and contain word "price"
                    $.each(result2, function(i, v) {
                        if(v.indexOf('price-') === -1) {
                            result3.push(v);
                        }
                    });

                    // put the new price param into it
                    var price_param_from    = this.vars.price_params[0];
                    var price_param_to      = this.vars.price_params[1];
                    var price_param         = 'price-';
                    if (price_param_to < price_param_from) {
                        price_param += price_param_to + '-' + price_param_from;
                    }else{
                        price_param += price_param_from + '-' + price_param_to;
                    }
                    result3.push(price_param);
                    
                    new_url_params = result3.join('-');
                }else{
                    new_url_params = result2.join('-');
                }

                // generate new url
                var new_url = this.vars.category_url + new_url_params;

                // add '.html' it if exists
                if (document.URL.indexOf('.html') >= 0) {
                    new_url  = new_url.replace(/\/$/, "");      // --> remove "/" at the end if exists
                    new_url += '.html';
                }

                // ----- debug
                this._log('### Generate new link ###');
                this._log('cur_params[]: ' + this.vars.cur_params);
                this._log('added_params[]: ' + this.vars.added_params);
                this._log('deleted_params[]: ' + this.vars.deleted_params);
                this._log('price_params[]: ' + this.vars.price_params);
                this._log('merge cur_params[] with added_params[]: ' +result1);
                this._log('merge cur_params[] with deleted_params[]: ' +result2);
                this._log('merge cur_params[] with price_params[]: ' +result3);
                this._log('new_url_params: ' +new_url_params);
                this._log('new_url: ' +new_url);
                this._log('----------------------------');
            }

            return new_url;
        }

    });
}(jQuery));
