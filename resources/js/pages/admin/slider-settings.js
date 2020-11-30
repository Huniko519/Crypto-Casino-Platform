import vm from '~/vue'
import Component from '../../components/admin/slider-settings'
import { mountVueComponent } from '~/utils'

let container = document.getElementById('slider-settings');
let props = JSON.parse(container.dataset.props);

mountVueComponent(Component, vm, container, props);
