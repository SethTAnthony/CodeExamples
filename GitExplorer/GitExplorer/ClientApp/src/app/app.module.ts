import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { NavMenuComponent } from './nav-menu/nav-menu.component';
import { SearchComponent } from './search/search.component';
import { DisplayUserComponent } from './display-user/display-user.component';
import { DisplayRepoComponent } from './display-repo/display-repo.component';
import { UserSearchDisplayComponent } from './user-search-display/user-search-display.component';
import { RepoSearchDisplayComponent } from './repo-search-display/repo-search-display.component';

@NgModule({
  declarations: [
    AppComponent,
    NavMenuComponent,
    SearchComponent,
    DisplayUserComponent,
    DisplayRepoComponent,
    UserSearchDisplayComponent,
    RepoSearchDisplayComponent
  ],
  imports: [
    BrowserModule.withServerTransition({ appId: 'ng-cli-universal' }),
    HttpClientModule,
    FormsModule,
    RouterModule.forRoot([
      { path: '', component: SearchComponent, pathMatch: 'full' },
      { path: 'user/:login', component: DisplayUserComponent, pathMatch: 'full' },
      { path: 'repo/:owner/:name', component: DisplayRepoComponent, pathMatch: 'full' }
    ])
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
