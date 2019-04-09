import { Component, OnInit, OnChanges, SimpleChanges, ViewChild } from '@angular/core'
import { SearchService } from '../search.service'
import { RepoSearchService } from '../repo-search.service'
import { UserSearchResults } from '../model/user-search-results'
import { RepoSearchResults } from '../model/repo-search-results'
import { UserSearchDisplayComponent } from '../user-search-display/user-search-display.component';
import { RepoSearchDisplayComponent } from '../repo-search-display/repo-search-display.component';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent implements OnInit{

  searchString: string
  searchRadio: string
  userResult: boolean = false;
  repoResult: boolean = false;

  @ViewChild(UserSearchDisplayComponent) userSearchDisplay: UserSearchDisplayComponent
  @ViewChild(RepoSearchDisplayComponent) repoSearchDisplay: RepoSearchDisplayComponent
  constructor(private searchService: SearchService,
    private repoSearchService: RepoSearchService) { }

  ngOnInit() {
  }

  search() {

    if (this.userSearchDisplay) {
      this.userSearchDisplay.searchString = this.searchString
    }

    //reset the search results
    this.userResult = false
    this.repoResult = false

    if (this.searchRadio == 'user') {
      if (this.userSearchDisplay) {
        this.userSearchDisplay.searchString = this.searchString
        this.userSearchDisplay.newSearch()
      }
      this.userResult = true
    } else if (this.searchRadio == 'repo') {
      if (this.repoSearchDisplay) {
        this.repoSearchDisplay.searchString = this.searchString
        this.repoSearchDisplay.newSearch()
      }
      this.repoResult = true
    }
  }
}
