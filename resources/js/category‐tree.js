document.addEventListener('DOMContentLoaded', () => {
    const options = window.treeSelectOptions || [];

    function findName(options, val) {
        function recurse(nodes) {
            for (const node of nodes) {
                if (node.value === val) {
                    return node.name;
                }
                if (node.children && node.children.length) {
                    const found = recurse(node.children);
                    if (found !== null) {
                        return found;
                    }
                }
            }
            return null;
        }

        const result = recurse(options);
        return result === null ? val : result;
    }

    const container = document.getElementById('category-tree');
    const Treeselect = window.Treeselect || require('treeselectjs').default;

    const tree = new Treeselect({
        parentHtmlContainer: container,
        options: options,
        value: [],
        placeholder: 'Search…'
    });

    tree.srcElement.addEventListener('input', (e) => {
        const selected = e.detail;
        document.getElementById('category_ids').value = selected;

        if (selected.length > 1) {
            const table = document.createElement('table');
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');

            selected.forEach(id => {
                const th = document.createElement('th');
                th.textContent = findName(options, id);
                headerRow.appendChild(th);
            });

            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            const bodyRow = document.createElement('tr');

            selected.forEach(id => {
                const td = document.createElement('td');
                const span = document.createElement('span');

                span.setAttribute('data-type', 'mergeTag');
                span.setAttribute('data-id', id);
                span.textContent = id;

                td.appendChild(span);
                bodyRow.appendChild(td);
            });

            tbody.appendChild(bodyRow);
            table.appendChild(tbody);
            window.tiptapEditor.chain().focus().insertContent(table.outerHTML).run();
        } else {
            selected.forEach(id => {
                const span = document.createElement('span');
                span.setAttribute('data-type', 'mergeTag');
                span.setAttribute('data-id', id);
                span.textContent = id;

                window.tiptapEditor.chain().focus().insertContent(span.outerHTML).run();
            });
        }

        tree.updateValue([]);
        tree.mount();
    });
});
