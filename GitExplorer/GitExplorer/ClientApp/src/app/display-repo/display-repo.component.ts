import { Component, OnInit } from '@angular/core'
import { Repo } from '../model/repo'
import { ParamMap, ActivatedRoute } from '@angular/router'
import { switchMap } from 'rxjs/operators'
import { Commit } from '../model/commit'
import { RepoSearchService } from '../repo-search.service'
import { Issue } from '../model/issue';

@Component({
  selector: 'app-display-repo',
  templateUrl: './display-repo.component.html',
  styleUrls: ['./display-repo.component.css']
})
export class DisplayRepoComponent implements OnInit {

  //data to be filled through api calls
  repo: Repo
  commits: Commit[]
  issues: Issue[]

  //flags used to stop the display of data that isn't yet initialized
  repoLoading: boolean = false
  commitsLoading: boolean = false
  issuesLoading: boolean = false
  searchFailed: boolean = false

  //used for tracking the currently displayed issues page
  currentIssuePage: number = 1
  totalIssuePages: number

  constructor(private route: ActivatedRoute,
    private repoSearchService: RepoSearchService) { }

  ngOnInit() {

    //make sure all api calls are finished before attempting to display data
    this.repoLoading = true
    this.commitsLoading = true
    this.issuesLoading = true

    //load repo data
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.repoSearchService.getRepo(params.get('owner') + '/' + params.get('name')))
    ).subscribe((data: Repo) => {

      this.repo = data
      this.repoLoading = false

      //call issues api; this needs to happen after the repo is already loaded
      this.route.paramMap.pipe(
        switchMap((params: ParamMap) =>
          this.repoSearchService.getIssues(params.get('owner') + '/' + params.get('name'), 1))
      ).subscribe((data: Issue[]) => {
        this.totalIssuePages = Math.ceil(this.repo.open_issues_count / 10)
        this.issues = data
        this.issuesLoading = false
        },
        (error: any) => {
          console.log(error)
          this.issuesLoading = false
          this.searchFailed = true
        })
      },
      (error: any) => {
        console.log(error)
        this.repoLoading = false
        this.searchFailed = true
      })

    //load commits data
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.repoSearchService.getCommits(params.get('owner') + '/' + params.get('name')))
    ).subscribe((data: Commit[]) => {

      //Only retrieve the first 5 elements of a search.
      this.commits = (data.length <= 5) ? data: data.splice(0, 5)

      this.commitsLoading = false
      },
      (error: any) => {
        console.log(error)
        this.commitsLoading = false
        this.searchFailed = true
      })

    
  }

  nextIssue() {
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.repoSearchService.getIssues(params.get('owner') + '/' + params.get('name'), ++this.currentIssuePage))
    ).subscribe((data: Issue[]) => {
      this.issues = data
      this.repoLoading = false
    })
  }

  prevIssue() {
    this.route.paramMap.pipe(
      switchMap((params: ParamMap) =>
        this.repoSearchService.getIssues(params.get('owner') + '/' + params.get('name'), --this.currentIssuePage))
    ).subscribe((data: Issue[]) => {
      this.issues = data
      this.repoLoading = false
    })
  }
}
