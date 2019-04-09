import { TestBed, inject } from '@angular/core/testing';

import { RepoSearchService } from './repo-search.service';

describe('RepoSearchService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [RepoSearchService]
    });
  });

  it('should be created', inject([RepoSearchService], (service: RepoSearchService) => {
    expect(service).toBeTruthy();
  }));
});
