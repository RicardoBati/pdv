$(document).ready(function() {
    var selectedProducts = [];
    var selectedProductIds = [];
    $('#product-search').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: '/produtos/buscar',
                data: { search: request.term },
                success: function(data) {

                    if (typeof data === 'string') {
                        data = JSON.parse(data);
                    }

                    response($.map(data.products, function(item) {
                        return {
                            label: item.nome,
                            value: item.nome,
                            id: item.id,
                            preco_venda: item.preco_venda,
                            valor_imposto: item.valor_imposto

                        };
                    }));
                },
                error: function() {
                    response([]);
                }
            });
        },
        select: function(event, ui) {
            selectedProductIds.push(ui.item.id);
            selectedProducts.push({ ...ui.item, quantidade: 1 });
            
            $("#selected-products-hidden").val(JSON.stringify(selectedProductIds));

            updateSelectedProductsDisplay();
            updateValorBruto();
            updateValorImposto();
            // Limpar o campo de busca de produto
            setTimeout(() => {
                $("#product-search").val('')
            }, 100);
        },
        minLength: 2
    });

    function updateSelectedProductsDisplay() {
        var selectedProductsTable = $("#selected-products tbody");
        selectedProductsTable.empty();
    
        selectedProducts.forEach(function(product, index) {
            var row = $('<tr></tr>');
            row.append('<td>' + product.id + '</td>');
            row.append('<td>' + product.label + '</td>');
            row.append('<td>' + parseFloat(product.preco_venda).toFixed(2) + '</td>');
    
            var quantityInput = $('<input type="number" class="form-control" value="' + product.quantidade + '" min="1">');
            quantityInput.on('change', function() {
                product.quantidade = parseInt($(this).val());
                updateValorBruto();
                updateValorImposto();
                updateImpostoForProduct(product, row.find('.imposto'));
            });
            row.append($('<td></td>').append(quantityInput));
    
            var impostoSpan = $('<span class="imposto"></span>');
            updateImpostoForProduct(product, impostoSpan);
            row.append($('<td></td>').append(impostoSpan));

            var deleteButton = $('<button class="btn btn-danger">Excluir</button>');
            deleteButton.on('click', function() {
                deleteProduct(index);
            });
            row.append($('<td></td>').append(deleteButton));
    
            selectedProductsTable.append(row);
        });
        $("#selected-products-hidden").val(JSON.stringify(selectedProductIds));
         // Mostra ou esconde o container de produtos selecionados
        $("#selected-products-container").toggle(selectedProducts.length > 0);
    }

    function deleteProduct(index) {
        selectedProducts.splice(index, 1);
        selectedProductIds.splice(index, 1);
        updateSelectedProductsDisplay();
        updateValorBruto();
        updateValorImposto();
    }
    
    function updateValorBruto() {
        var total = selectedProducts.reduce(function(sum, product) {
            return sum + (parseFloat(product.preco_venda) * product.quantidade);
        }, 0);
        
        $("#valor_bruto").val(total.toFixed(2));
    }

    function updateImpostoForProduct(product, impostoSpan) {
        var impostoValue = parseFloat(product.preco_venda) * (parseFloat(product.valor_imposto) / 100) * product.quantidade;
        impostoSpan.text('Imposto: R$ ' + impostoValue.toFixed(2));
    }

    function updateValorImposto() {
        var totalImposto = selectedProducts.reduce(function(sum, product) {
            // Calcula o valor do imposto para o produto atual
            var imposto = parseFloat(product.preco_venda) * (parseFloat(product.valor_imposto) / 100);
            // Soma o valor do imposto ao total
            return sum + imposto * product.quantidade;
        }, 0);
        
        $("#valor_imposto").val(totalImposto.toFixed(2));
    }
    
    $("#formOrder").on("submit", function(event) {
        event.preventDefault();

        if (selectedProducts.length === 0) {
            Swal.fire(
                'Erro!',
                'Nenhum produto selecionado!',
                'error'
            );
            return false
        }
        // Serializa o formulário
        var formData = $(this).serializeArray();

        // Adiciona os produtos selecionados ao payload
        formData.push({
            name: "products",
            value: JSON.stringify(selectedProducts)
        });
        $.ajax({
            url: '/venda',
            method: "POST",
            data: formData,
            success: function(response) {
                if (typeof response === 'string') {
                    response = JSON.parse(response);
                }
                if (!response.status) {
                    Swal.fire(
                        'Erro!',
                        response.mensagem,
                        'error'
                    );
                }else{
                    Swal.fire({
                        title: 'Sucesso!',
                        text: response.mensagem,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '/vendas';
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Houve um problema ao enviar os dados.',
                    icon: 'error',
                    showConfirmButton: true
                });
            }
        });
    });
});