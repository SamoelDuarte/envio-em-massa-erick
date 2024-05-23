const _token = $("input[name=_token]").val();



const Utils = {
    init: () => {
        Utils.setGlobalConfig();
    },
    setGlobalConfig: () => {
        $(".global-form").submit(() => {
            Utils.setFormsInProcessingMode();
        });

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": _token,
            },
        });

        $(".toggle-right-bar").click(() => {
            $("body").toggleClass("right-bar-enabled");
        });

        $(".title-case").each((i, el) => {
        
            $(el).keyup(() => {
                $(el).val(Utils.formatProperPersonName($(el).val()));
            })
        })
    },
    setFormsInProcessingMode: () => {
        $(".submit").html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
        );
        $(".submit").prop("disabled", true);
    },

    formatProperUsername: (str) => {
        return _.deburr(str.replaceAll(" ", "").toLowerCase());
    },

    formatProperPersonName: (text) => {
        text = text.toLowerCase();
        let preps = [
            " De ",
            " Da ",
            " Do ",
            " Dos ",
            " E ",
            " O ",
            " Em ",
            " Ou ",
            " Os ",
            " Das ",
        ];
        text = text.replace(/(^|\s)\S/g, function (t) {
            return t.toUpperCase();
        });
        let capitalizedText = text;
        preps.map((prep) => {
            capitalizedText = capitalizedText.replaceAll(
                prep,
                prep.toLowerCase()
            );
        });
        return capitalizedText;
    },

    showPassword(el_id, btn_id) {
        let typeIsPassword = $(`#${el_id}`).prop("type") == "password";
        if (typeIsPassword) {
            $(`#${el_id}`).prop("type", "text");
            $(`#${btn_id}`).html(`ocultar`);
            return false;
        }

        //password is already text. set its type to password again
        $(`#${el_id}`).prop("type", "password");
        $(`#${btn_id}`).html(`exibir`);
    },

    redirect: (to) => {
        window.location.href = to;
    },

    bytesToKBytes: (bytes) => {
        return (bytes / 1024).toFixed(2).toString() + "KB";
    },

    
    uuid: () => {
        let dt = new Date().getTime();
        let uuid = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
            /[xy]/g,
            function (c) {
                var r = (dt + Math.random() * 16) % 16 | 0;
                dt = Math.floor(dt / 16);
                return (c == "x" ? r : (r & 0x3) | 0x8).toString(16);
            }
        );
        return uuid;
    },
    removeElement: (elId) => {
        $(elId).remove();
    },
    setupPagination: (data) => {
        const links = Utils.sanitizeLinks(data.data);

        $("#pagination").html(`
        <nav aria-label="Page navigation example">
           <ul class="pagination">
              ${links
                  .map((link) =>
                      `
                 <li style="cursor: pointer" class="page-item ${
                     link.active ? "active" : ""
                 } ${link.page ? "" : "disabled"}">
                    <a class="page-link" ${
                        link.page ? "" : "disabled"
                    } onclick="${data.get}({page: ${link.page}})">${
                          link.label
                      }</a>
                 </li>
              `.trim()
                  )
                  .join("")}
           </ul>
        </nav>
        
        `);
    },
    sanitizeLinks: (data) => {
        let sanitezedLinks = [];
        sanitezedLinks.push({
            active: false,
            page: data.first_page_url
                ? data.first_page_url.split("page=")[1]
                : null,
            label: "Primeira",
        });
        data.links.map((value, i) => {
            if (i == 0) {
                value.label = "&laquo;";
            }
            if (i == data.links.length - 1) {
                value.label = "&raquo;";
            }
            let page = value.url ? value.url.split("page=")[1] : null;
            sanitezedLinks.push({
                active: value.active,
                page: page,
                label: value.label,
            });
        });

        sanitezedLinks.push({
            active: false,
            page: data.last_page_url
                ? data.last_page_url.split("page=")[1]
                : null,
            label: "Última",
        });
        return sanitezedLinks;
    },
    isLoading: (show = true) => {
        if (show) {
            $("#is-loading").css("display", "flex");
            return false;
        }
        $("#is-loading").css("display", "none");
    },
    fileToBase64: (file) => 
        new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
        }),
};

Utils.init();

$(document).ready(function() {
    $('#cnpj').inputmask("99.999.999/9999-99", {
        placeholder: " ",
        clearMaskOnLostFocus: false,
    });
});

let SPMaskBehaviorPhone = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 0 0000-0000' : '(00) 0000-00009';
},
spOptionsPhone = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehaviorPhone.apply({}, arguments), options);
    }
};
$('.telefone').mask(SPMaskBehaviorPhone, spOptionsPhone);


