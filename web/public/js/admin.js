$(function() {
    $('.readonly-form').find('input').prop({
        'readonly': true,
        'disabled': true
    });
    //init_text_editor();
    //init_date_picker();
    
    
    $('body').on('hidden.bs.modal', '.modal', function() {
        $(this).removeData('bs.modal');
    });
    //$('.alert-flash').slideUp(3000);
    $(document).on('click', '.btn-ajax', function(e) {
        //Formaulaires AJAX
        e.preventDefault();
        e.stopImmediatePropagation();
        const $this = $(this);
        const $form = $this.closest('form');
        const form_id = $form.attr('id');
        const $loader = $form.find('.loader');
        const $modal = $this.closest('.modal');
        $form.ajaxSubmit({
            cache: false,
            beforeSend: () => {
                $loader.removeClass('hide');
            },
            complete: () => {
                $loader.addClass('hide');
            },
            success: (data, status, $xhr, $form) => {
                const close_html = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                const message = data.message;
                const redirect = data.redirect;
                const redirectFull = data.redirectFull;

                if (data.statut) {
                    $('.ajax-success', $form).removeClass('hide').html(close_html + message);
                    $('.ajax-error', $form).addClass('hide');
                    if (redirect.indexOf('#') === -1) {
                        //console.log('Foo00');
                        if ($modal.length && (typeof data.modal =='undefined' || data.modal === false)) {
                            $modal.modal('toggle');
                            //$('#modal-table').modal('toggle');
                        }


                        if (!data.redirectFull) {
                            reload_page(redirect, data.ajax);
                        } else {
                            document.location.href = redirect;
                        }
                       
                       
                        /*$('#page-loader').removeClass('display-none');
                        $('.page-content-inner').load(`${redirect} #page-content-wrapper`, () => {
                            $('#page-loader').addClass('display-none');
                            if ($('.content-tab').length) {
                                const id = $('.content-tab').attr('id');
                                const storage_key = `${id}_current_index`.replace('-', '_');
                                const current_index = localStorage.getItem(`${id}_current_index`) || 0;
                                $(`#${id} li:eq(${current_index}) a`).tab('show');
                            }
                        });*/
                    } else {
                        //console.log('DEMOOOO');
                        const [url, modal_id] = redirect.split('#');

                        /*if (modal_id == 'modal-table') {
                        
                            $('#modal-table').removeClass('reload-page').modal('hide');
                        }*/
                        if (modal_id == $modal.attr('id')) {
                            $modal.removeClass('reload-page').modal('hide');
                        }
                        //console.log(url, modal_id);
                        $('#' + modal_id).modal('toggle');
                        const grid_hash = localStorage.getItem('psm__grid_hash');
                        const $grid_wrapper = $('#grid-wrapper-'+grid_hash, $modal);

                        //$('#modal-table').addClass('reload-page');
                        $modal.addClass('reload-page');

                        if ($grid_wrapper.length) {
                             const $grid_loader = $grid_wrapper.find('.grid-overlay');
                            $grid_loader.removeClass('display-none');
                        
                            //$('#modal-table')
                                $modal  
                                .find('#grid-wrapper-'+grid_hash)

                                .load(url + ' #grid-table-' + grid_hash, function() {
                                    $grid_loader.addClass('display-none');
                                });
                        }
                       
                        
                    }
                    /*setTimeout(function () {
                        document.location.href = redirect;
                    },1000);*/
                } else {

                    let tpl = '';
                    if (Array.isArray(message)) {
                        for (let _message of message) {
                            tpl += `<p class="small">${_message}</p>`;
                        }
                    } else {
                        tpl = message;
                    }
                    $('.ajax-error', $form).removeClass('hide').html(close_html + tpl);
                    $('.ajax-success', $form).addClass('hide');
                    $modal.scrollTop($('.ajax-error', $form).position().top);
                    /*$('.ajax-error', $form).removeClass('hide').html(close_html + tpl);
                    $('.ajax-success', $form).addClass('hide');*/
                    var pos = $('.ajax-error', $form).position().top;
                    if ($modal.length) {
                        $modal.scrollTop(pos)
                    } else {
                        $(document).scrollTop(pos);
                    }
                    
                }
            },
            error: (data) => {
                $('.ajax-error', $form).removeClass('hide').html('Erreur interne du serveur');
                $('.ajax-success', $form).addClass('hide');
                $modal.scrollTop($('.ajax-error').position().top);
            }
        });
    }).on('click', '.prevent-default', function (e) {
        e.preventDefault();
    });




    $('.modal').on('hidden.bs.modal', function (e) {
        const $this = $(this);
        const $target = $(e.currentTarget);
        //console.log($this, $target);
        if ($target.is($this) && !$this.hasClass('in')) {
            //console.log(e.type);
            //$target.modal('show');
        }
    });
    $('.modal').on('hide.bs.modal', function(e) {
        const $this = $(this);
        //console.log(e);
        /*if ($this.attr('id') != 'full') {
            $this.find('.modal-dialog').removeClass('modal-full');
        }

        ///$this.find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg');

        if ($this.attr('id') == 'stack2') {
            $this.find('.modal-dialog').removeClass('modal-lg');
        }*/

        //console.log('Bam');

        //const $target = $(e.relatedTarget);
        //console.log(e.relatedTarget);
        
         //$this.removeClass('modal-fullscreen');
        const default_template = `
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                       <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body text-center">
                    <img src="${loader_path}" alt="" class="loading">
                    <span> &nbsp;&nbsp;Chargement des données... </span>
                </div>
            `;
        $this.find('.modal-content').html('').append(default_template);

        if ($this.hasClass('reload-page')) {
            $this.removeClass('reload-page');
            reload_page(current_url);
            $('.alert-flash').remove();
        }
    });


    $('.modal').on('show.bs.modal', function (e) {
        const $target = $(e.relatedTarget);
        const $this = $(this);
        
        if ($target.hasClass('has-full-modal') || $target.hasClass('modal-full')) {
            $(this).find('.modal-dialog').addClass('modal-full');
        }

        if ($target.hasClass('has-lg-modal')) {
            $this.find('.modal-dialog').addClass('modal-lg');
        }

         if ($target.hasClass('has-sm-modal')) {
            $this.find('.modal-dialog').addClass('modal-sm');
        }

         

    });
    $('.modal').on('shown.bs.modal', function(e) {
        //const $target = $(e.relatedTarget);
        const $this = $(this);
        localStorage.setItem('current_modal_id', $this.attr('id'));
       
        
    
        //init_select2('select');
    });


    $(document).ajaxStart(function(e) {
        //console.log(e);
    }).ajaxError(function (e) {
        
    }).ajaxComplete(function () {
      // setUserFields();
    });

    function reload_page(url/*, index = 0*/, ajax) {
       
        ajax = typeof ajax == 'undefined' ? true : ajax;
        if (!ajax) {
            document.location.href = url;
        } else {
             $('#page-loader').removeClass('display-none');
            $('#content-inner').load(`${url} #page-content-wrapper`, () => {
                $('#page-loader').addClass('display-none');
                /*if ($('.content-tab').length) {
                    const id = $('.content-tab').attr('id');
                    const storage_key = `${id}_current_index`.replace('-', '_');
                    const current_index = localStorage.getItem(storage_key) || index;
                    $(`#${id} li:eq(${current_index}) a`).tab('show');
                }*/

                 $('.alert-flash').slideUp(3000);
            });
        }
       
    }


    

   
});