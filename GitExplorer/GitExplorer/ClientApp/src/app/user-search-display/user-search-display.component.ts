import { Component, OnInit, Input, ComponentRef } from '@angular/core'
import { UserSearchResults } from '../model/user-search-results'
import { SearchService } from '../search.service';

@Component({
  selector: 'app-user-search-display',
  templateUrl: './user-search-display.component.html',
  styleUrls: ['./user-search-display.component.css']
})
export class UserSearchDisplayComponent implements OnInit {

  @Input() searchString: string
  results: UserSearchResults
  currentPage: number = 1
  totalPages: number
  loading: boolean = false
  searchFailed: boolean = false

  constructor(private searchService: SearchService) { }

  ngOnInit() {
    this.loading = true
    this.searchService.searchForUsers(this.searchString, 1)
      .subscribe(
        (data: UserSearchResults) => {
          this.currentPage = 1
          this.results = data
          this.totalPages = Math.ceil(this.results.total_count / 10)
          this.loading = false
          this.searchFailed = false
      },
      (error: any) => {
        console.log(error)
        this.loading = false
        this.searchFailed = true
      })
  }

  newSearch() {
    this.loading = true
    this.searchService.searchForUsers(this.searchString, 1)
      .subscribe(
        (data: UserSearchResults) => {
          this.currentPage = 1
          this.results = data
          this.totalPages = Math.ceil(this.results.total_count / 10)
          this.loading = false
          this.searchFailed = false
      },
      (error: any) => {
        console.log(error)
        this.loading = false
        this.searchFailed = true
      })
  }

  nextPage() {
    this.loading = true
    this.searchService.searchForUsers(this.searchString, ++this.currentPage)
      .subscribe(
        (data: UserSearchResults) => {
          this.results = data
          this.loading = false
          this.searchFailed = false
      },
      (error: any) => {
        console.log(error)
        this.loading = false
        this.searchFailed = true
      })
  }

  prevPage() {
    this.loading = true
    this.searchService.searchForUsers(this.searchString, --this.currentPage)
      .subscribe(
        (data: UserSearchResults) => {
          this.results = data
          this.loading = false
          this.searchFailed = false
      },
      (error: any) => {
        console.log(error)
        this.loading = false
        this.searchFailed = true
      })
  }
}
