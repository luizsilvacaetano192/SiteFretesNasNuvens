columns: [
    {
        className: 'dt-control',
        orderable: false,
        data: null,
        defaultContent: ''
    },
    { data: 'name', name: 'name' },
    { data: 'address', name: 'address' },
    {
        data: 'identity_card',
        name: 'identity_card',
        render: function(data) {
            if (!data) return '';
            return data.replace(/^(\d{2})(\d{3})(\d{3})(\d{1})$/, "$1.$2.$3-$4");
        }
    },
    {
        data: 'phone',
        name: 'phone',
        render: function(data) {
            if (!data) return '';
            return data.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
        }
    },
    {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            return `
                <div class="btn-group btn-group-sm" role="group">
                    <a href="/drivers/${row.id}/balance" class="btn btn-outline-success">💰 Saldo</a>
                    <a href="/drivers/${row.id}/freights" class="btn btn-outline-primary">🚚 Ver Fretes</a>
                    <button onclick="activateDriver(${row.id})" class="btn btn-outline-warning">✅ Ativar</button>
                    <button onclick="analyzeDriver(${row.id})" class="btn btn-outline-dark">🕵️ Analisar</button>
                </div>
            `;
        }
    }
],
