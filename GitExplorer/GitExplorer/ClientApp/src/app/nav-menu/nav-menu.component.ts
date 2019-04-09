import { Component } from '@angular/core'
import { AngularFontAwesomeModule } from 'angular-font-awesome'

@Component({
  selector: 'app-nav-menu',
  templateUrl: './nav-menu.component.html',
  styleUrls: ['./nav-menu.component.css']
})
export class NavMenuComponent {
  isExpanded = false

  collapse() {
    this.isExpanded = false
  }

  toggle() {
    this.isExpanded = !this.isExpanded
  }
}
