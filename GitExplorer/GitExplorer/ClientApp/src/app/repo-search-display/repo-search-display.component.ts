import { Component, OnInit, Input } from '@angular/core'
import { RepoSearchResults } from '../model/repo-search-results'
import { RepoSearchService } from '../repo-search.service';

@Component({
  selector: 'app-repo-search-display',
  templateUrl: './repo-search-display.component.html',
  styleUrls: ['./repo-search-display.component.css']
})
export class RepoSearchDisplayComponent implements OnInit {

  @Input() searchString: string
  results: RepoSearchResults
  currentPage: number = 1
  totalPages: number
  loading: boolean = false
  searchFailed: boolean = false

  constructor(private repoSearchService: RepoSearchService) { }

  ngOnInit() {
    this.loading = true
    this.repoSearchService.searchForRepos(this.searchString, 1)
      .subscribe(
        (data: RepoSearchResults) => {
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
    this.repoSearchService.searchForRepos(this.searchString, 1)
      .subscribe(
        (data: RepoSearchResults) => {
          this.results = data
          this.currentPage = 1
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
    this.repoSearchService.searchForRepos(this.searchString, ++this.currentPage)
      .subscribe(
        (data: RepoSearchResults) => {
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
    this.repoSearchService.searchForRepos(this.searchString, --this.currentPage)
      .subscribe(
        (data: RepoSearchResults) => {
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
