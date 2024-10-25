/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class autocompleteSearchResultContainer
{
    constructor(origin)
    {
        let pos = this.calcSearchContainerPosition(origin);
        this.elm = document.createElement('div');
        this.elm.style.cssText = 'position: absolute; top:' + pos.top + 'px; left : ' + pos.left + 'px; width: ' + pos.width + 'px; max-height: ' + pos.height + 'px';
        this.elm.style.display = 'none';
        this.elm.dataset.parent = origin.getAttribute('id');
        this.elm.setAttribute('id', 'autocompleteSearchContainer' + origin.getAttribute('id'));
        this.elm.classList.add('osy-autocomplete-listbox');
        this.elm.addEventListener('click', this.selectRow);
        this.elm.arrowUp = this.arrowUp;
        this.elm.arrowDown = this.arrowDown;
        this.elm.show = () => this.elm.style.display = 'block';
        this.elm.hide = () => this.elm.style.display = 'none';
        this.elm.origin = origin;
        return this.elm;
    }

    calcSearchContainerPosition(origin)
    {
        let originPos = this.position(origin);
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        let containerPos = {
            top   : originPos.bottom,
            left  : originPos.left,
            width : originPos.width,
            height: Math.max(100, windowHeight - (originPos.bottom + 50))
        };
        if (500 > (windowWidth - originPos.left)) {
            //console.log('angolo destro', parentWidth, parentPosition.left, windowWidth);
            //Posiziono il SearchContainer partendo dall'angolo destro del componente
            //containerPos.left = originPos.right - 500;
            //containerPos.width = 500;
        }
        return containerPos;
    }

    position(origin)
    {
        let rect = origin.getBoundingClientRect();
        let position = {
            width : origin.offsetWidth,
            height : origin.offsetHeight,
            top: rect.top + window.scrollY,
            left: rect.left + window.scrollX
        };
        position.right = position.left + position.width;
        position.bottom = position.top + position.height;
        return position;
    }

    arrowUp = function()
    {
        let rowSelected = this.querySelector('.item.selected');
        if (!rowSelected) {
            this.lastChild.classList.add('selected');
        } else if(this.firstChild === rowSelected){
            rowSelected.classList.remove('selected');
            this.lastChild.classList.add('selected');
        } else {
            rowSelected.classList.remove('selected');
            rowSelected.previousSibling.classList.add('selected');
        }
    }

    arrowDown()
    {
        let rowSelected = this.querySelector('.item.selected');
        if (!rowSelected) {
            this.querySelector('.item').classList.add('selected');
        } else if(rowSelected.nextSibling === null){
            rowSelected.classList.remove('selected');
            this.querySelector('.item').classList.add('selected');
        } else {
            rowSelected.classList.remove('selected');
            rowSelected.nextSibling.classList.add('selected');
        }
    }

    selectRow(ev)
    {        
        if (!ev.target || !ev.target.matches('div.item')) {
            return;
        }
        let self = ev.target;
        if (self.classList.contains('empty-message')) {
            return;
        }        
        ev.preventDefault();
        let searchContainer = self.closest('div.osy-autocomplete-listbox');
        let autocomplete = searchContainer.origin.closest('div.osy-autocomplete');
        searchContainer.origin.classList.remove('osy-autocomplete-unselected');
        searchContainer.origin.value = self.dataset.label;
        autocomplete.querySelector('input[type=hidden]').value = self.dataset.value;
        if (autocomplete.getAttribute('onselect')) {
            eval(autocomplete.getAttribute('onselect'));
        }
        searchContainer.hide();
    }
}

BclAutocomplete =
{
    timeoutHandles : [],
    init : function()
    {        
        Osynapsy.element('body')
        .on('keydown', 'div.osy-autocomplete input[type=text]', ev => BclAutocomplete.keyPressDispatcher(ev))
        .on('focusout', 'div.osy-autocomplete input[type=text]', ev => setTimeout(() => BclAutocomplete.getSearchContainer(ev.target).hide(), 500));         
    },
    keyPressDispatcher : function(ev)
    {
        let timeBeforeSelectSingleResult = 1000;
        let searchContainer = BclAutocomplete.getSearchContainer(ev.target);
        switch (ev.keyCode) {
            case 9: //Tab
                break;
            case 13 : //Enter
                let selectedRow = searchContainer.querySelector('.item.selected');
                if (selectedRow) { selectedRow.click(); }
                break;           
            case 27 : //Esc
                ev.preventDefault();
                searchContainer.hide();
                break;
            case 38 : //Up
                ev.preventDefault();
                searchContainer.arrowUp();
                break;
            case 40 : //Down
                ev.preventDefault();
                searchContainer.arrowDown();
                break;            
            case 8: //Backspace
                timeBeforeSelectSingleResult = false;            
            default:
                this.updateSearch(ev.target, searchContainer, timeBeforeSelectSingleResult);                                
                break;
        }
    },
    updateSearch : function(elm, searchContainer, timeBeforeSelectSingleResult) {
        this.clearTimeouts();
        if (elm.value === '') {
            searchContainer.hide();
            return;
        }        
        this.timeoutHandles.push(setTimeout(
            () => BclAutocomplete.refreshSearchResult(elm, searchContainer, timeBeforeSelectSingleResult), 
            600
        ));
    },
    clearTimeouts : function ()
    {
        this.timeoutHandles.forEach(timeoutId => clearTimeout(timeoutId));        
        this.timeoutHandles = [];
    },
    refreshSearchResult : function(origin, searchContainer, timeBeforeSelectSingleResult)
    {
        origin.classList.add('osy-autocomplete-unselected');
        origin.closest('div.osy-autocomplete').querySelector('input[type=hidden]').value = '';
        window.fetch(window.location.href, {
            method : 'post',
            headers: {
                'Osynapsy-Html-Components': origin.getAttribute('id'),
                'Accept': 'text/html'
            },
            body : new FormData(origin.closest('form'))
        })
        .then(response => response.text())
        .then(response => {
            let parser = new DOMParser();
            let htmlDoc = parser.parseFromString(response, 'text/html');
            let items = htmlDoc.querySelectorAll('.item');
            if (items.length === 0) {
                searchContainer.hide();
                return;
            }
            searchContainer.innerHTML = '';
            items.forEach(item => searchContainer.appendChild(item));
            searchContainer.show();
            if ($(items).hasClass('empty-message')) {
                BclAutocomplete.timeoutHandles.push(setTimeout(() => searchContainer.hide(),  2000));
            } else if (items.length === 1 && timeBeforeSelectSingleResult) {
                BclAutocomplete.timeoutHandles.push(setTimeout(() => $('.item').click(), timeBeforeSelectSingleResult));
            }
        })
        .catch(error => console.log(error));
    },
    getSearchContainer(origin)
    {
        let originId = origin.getAttribute('id');
        if (!(originId in this.searchContainers)) {
            this.searchContainers[originId] = this.searchContainerFactory(origin);
            document.body.append(this.searchContainers[originId]);
        }
        return this.searchContainers[originId];
    },
    searchContainerFactory : function(origin)
    {
        return new autocompleteSearchResultContainer(origin);
    },
    searchContainers : {}
};

if (window.Osynapsy) {
    Osynapsy.plugin.register('BclAutocomplete', function() {
        BclAutocomplete.init();
    });
}