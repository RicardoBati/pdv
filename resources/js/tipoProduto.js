$(document).ready(function() {
    $('#formTipoProduto').on('submit', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do formulário

        var formData = $(this).serialize(); // Serializa os dados do formulário
        var url = '/novoTipoProduto';
        var id = $('#id_tipo_produto').val();

        if (id) {
            url = '/tipoProduto/'+id+'/edit';
        }
        $.ajax({
            type: 'POST',
            url: url,
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
                        timer: 2000, 
                        showConfirmButton: false 
                    }).then(() => {
                        window.location.href = '/tipoProduto';
                    });
                }
            },
            error: function(error) {
                Swal.fire(
                    'Erro!',
                    'Ocorreu um erro ao cadastrar o tipo do produto.',
                    'error'
                );
            }
        });
    });
    
    $('.deleteTypeProduct').on('click', function(event) {
        var productId = $(this).attr('data-id');
        // Mostra a mensagem de confirmação
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '/tipoProduto/delete',
                    data: { id: productId },
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
                                title: 'Excluído!',
                                text: response.mensagem,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '/tipoProduto';
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao excluir.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});