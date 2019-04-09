import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DisplayRepoComponent } from './display-repo.component';

describe('DisplayRepoComponent', () => {
  let component: DisplayRepoComponent;
  let fixture: ComponentFixture<DisplayRepoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DisplayRepoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DisplayRepoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
