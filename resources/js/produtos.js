$(document).ready(function() {
    $('.deleteProduct').on('click', function(event) {
        var productId = $(this).attr('data-id');
        // Mostra a mensagem de confirmação usando
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
                    url: '/produto/deletar',
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
                                window.location.href = '/produtos';
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao excluir o produto.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $('#formproduct').on('submit', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do formulário

        var formData = $(this).serialize(); // Serializa os dados do formulário
        var url = '/novoProduto';
        var id = $('#id_produto').val();

        if (id) {
            url = '/produto/'+id+'/edit';
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
                        window.location.href = '/produtos';
                    });
                }
            },
            error: function(error) {
                Swal.fire(
                    'Erro!',
                    'Ocorreu um erro ao cadastrar o produto.',
                    'error'
                );
            }
        });
    });
});