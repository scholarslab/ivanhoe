require 'spec_helper'

describe "Game Views", :type => :feature, :js => true  do

  before(:each) do
    visit(URL_BASE)
    click_link('Games')
    find('article.game').first(:link).click
  end

  describe "archive-ivanhoe_game layout" do

    it "has a title header" do
      within('article.game') { expect(page).to have_selector('h1') }
    end

    it "has a 'Playing since' line" do
      expect(page).to have_content('Playing since:')
    end

    it "has a list of moves" do
      within('#moves') { expect(page).to have_selector('article.move') }
    end

    it "has a link to an individual move" do
      pending
    end

    describe "individual move" do
      before(:each) do
        page.find('#moves article:first-child')
      end

      it "has a header" do
        expect(page).to have_selector('header h1')
      end

      it "has a byline" do
        expect(page).to have_selector('header .byline')
      end

      it "has a date" do
        expect(page).to have_selector('header time')
      end

      it "has an excerpt" do
        expect(page).to have_selector('div.excerpt')
      end

      it "has discussion source" do
        expect(page).to have_selector('div.game-discussion-source')
      end

      it "has discussion response" do
        expect(page).to have_selector('div.game-discussion-response')
      end

    end

    describe "addtional game data" do

      it "has a character header" do
        expect(page).to have_content('Characters')
      end

      it "has a list of chacters playing the game" do
        within('#game-data') { expect(page).to have_selector('ul.character_list') }
      end

      # TODO: once the fixtures are in place, test conditional inclusion of the data

      it "has a game description header" do
        expect(page).to have_content('Game Description')
      end

      it "has a game description" do
        description = page.find('#game-data p')
        expect(description).to have_content
      end

    end

    describe "authenticated views" do
      before :each do
        click_link('Games')
        click_link('Log in')
        fill_in 'Username', with: ENV['VALID_USER']
        fill_in 'Password', with: ENV['VALID_PASSWORD']
        click_button 'Log In'
      end

      it "redirects to games listing when you log in" do
        expect(page).to have_content('Games')
      end

      it  "has a 'Make a Game' link" do
        expect(page).to have_link('Make a Game')
      end

      describe "making a new game" do
        before :each do
          click_link("Make a Game")
        end

        it "has a required 'Game Title'" do
          pending
        end

        it "has an optional 'Game Thumbnail'" do
          pending
        end

        it "has a required 'Game Description'" do
          pending
        end
      end


    end

  end

end
