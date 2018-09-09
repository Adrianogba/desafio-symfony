const empresas = document.getElementById('empresas');

if (empresas) {
  empresas.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-empresa') {
      if (confirm('Tem certeza?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/empresa/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}
