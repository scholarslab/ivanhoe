require 'spec_helper'

def tiny_mce_fill_in(name, args)
  page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
end

describe "Game View", :type => :feature, :js => true  do

  @valid_game = {
    :game_title => Faker::Lorem.words(rand(2..8)),
    :game_description => Faker::Lorem.paragraphs(rand(1..3))
  }

  def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
  end

  def make_game
    click_link 'Make a Game'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def make_a_move
    click_link 'Make a move'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  before(:each) do
    visit(URL_BASE)
    click_link 'Games'
    login
    rand(2..5).times { make_game }
  end

  describe "An individual game page" do

    before do
      first('.game-title a').click
    end

    describe "with no moves" do

      it "has a game detail block" do
        within('.game') {expect(page).to have_selector('#game-data')}
      end

      it "has a 'Playing since' line" do
        expect(page).to have_content('Playing since:')
      end

      it "has a line saying there are no moves" do
        expect(page).to have_content('There are no moves for this game.')
      end

      describe "and no role" do

        it "has a Make a Role! button" do
          expect(page).to have_link('Make a Role!')
        end

      end

      describe "with a role" do

        before do
          make_role
        end

        it "identifies your current role" do
          expect(page).to have_content("Your Current Role")
        end

        it "has a link to your role" do
          expect(page).to have_selector('.role a')
        end

        it "lists other characters" do
          expect(page).to have_selector(".character_list")
        end

        it "has the Make a move button" do
          expect(page).to have_selector('#make-a-move')
        end

      end

      describe "with moves" do

        before do
          make_role
          make_a_move
        end

        it "has a move" do
          expect(page).to have_selector('article.ivanhoe_move')
        end

        it "has the title of a move" do
          expect(page).to have_selector('.ivanhoe_move')
        end

        it "has a link to the individual move page" do
          expect(page).to have_selector('.ivanhoe_move h1 a')
        end

        it "has a link to your role within the move block" do
          expect(page).to have_selector(".byline a")
        end

        it "has the Respond to move button" do
          expect(page).to have_link('Respond')
        end

        it "has the move excerpt" do
          expect(page).to have_selector('.excerpt')
        end

      end

    end

  end

      ##TODO this is conditional
      ##it "has a list of moves" do
      ##within('#moves') { expect(page).to have_selector('article.move') }
      ##end

      #it "has a link to an individual move" do
      #pending
      #end

      #describe "individual move" do
      ## TODO refactor to show
      #before(:each) do
      #page.find('#moves article:first-child')
      #end

      #it "has a header" do
      #expect(page).to have_selector('header h1')
      #end

      #it "has a byline" do
      #expect(page).to have_selector('header .byline')
      #end

      #it "has a date" do
      #expect(page).to have_selector('header time')
      #end

      #it "has an excerpt" do
      #expect(page).to have_selector('div.excerpt')
      #end

      #it "has discussion source" do
      #expect(page).to have_selector('div.game-discussion-source')
      #end

      #it "has discussion response" do
      #expect(page).to have_selector('div.game-discussion-response')
      #end


    #describe "addtional game data" do

    # TODO need to conditionally test this stuff if it exists from moves
    #it "has a character header" do
    #expect(page).to have_content('Characters')
    #end

    #it "has a list of chacters playing the game" do
    #within('#game-data') { expect(page).to have_selector('ul.character_list') }
    #end

    ## TODO: once the fixtures are in place, test conditional inclusion of the data

    #it "has a game description header" do
    #expect(page).to have_content('Game Description')
    #end

    #it "has a game description" do
    #description = page.find('#game-data p')
    #expect(description).to have_content
    #end

    #end

    #describe "authenticated views" do
    #before :each do
    #click_link('Games')
    #click_link('Log in')
    #fill_in 'Username', with: ENV['VALID_USER']
    #fill_in 'Password', with: ENV['VALID_PASSWORD']
    #click_button 'Log In'
    #end

    #it "redirects to games listing when you log in" do
    #expect(page).to have_content('Games')
    #end

    #it  "has a 'Make a Game' link" do
    #expect(page).to have_link('Make a Game')
    #end

    #describe "making a new game" do

    #valid_game = {
    #:game_title => 'Rosetti\'s "Jenny"',
    #:game_description => "A reproduction of an Ivanhoe Game played in 2003 with Dante Gabriel Rossetti’s poem, “Jenny.” Players included Jerome McGann (“Leonardo”), Bethany Nowviskie (“ISP Industries”) and Andrea Laue (“quiotl”).  The game was later published by Romantic Circles and Literature Compass."
    #}

    #before :each do
    #click_link("Make a Game")
    #end

    #def tiny_mce_fill_in(name, args)
    #page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
    #end

    #it "has a 'Save' button" do
    #expect(page).to have_button('Save')
    #end

    #it "has a required 'Game Title'" do
    #expect(page).to have_field('post_title')
    #click_button 'Save'
    #expect(page).to have_content('A title is required')
    #end

    #it "has an optional 'Game Thumbnail'" do
    #expect(page).to have_field('post_thumbnail')
    #end

    #it "has a required 'Game Description'" do
    #expect(page).to have_css('#wp-post_content-wrap')
    #click_button 'Save'
    #expect(page).to have_content('A description is required')
    #end

    #it "has the ability to 'Add Media'" do
    #expect(page).to have_link('Add Media')
    #end

    #it "can create a valid game" do
    #fill_in 'post_title', :with => valid_game[:game_title]
    #tiny_mce_fill_in('post_content', :with => valid_game[:game_description])
    #click_button('Save')
    #end

    #it "has a link to create a role" do
    #click_link("Games")
    #first('.game-title > a').click

    #expect(page).to have_content('Make a Role!')
    #end
    #end


    #end

    #end

  end


