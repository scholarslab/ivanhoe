require 'spec_helper'

describe "Single Game View", :type => :feature, :js => true  do

  include ApplicationHelper

  @valid_game = {
    :game_title => Faker::Lorem.words(rand(2..8)),
    :game_description => Faker::Lorem.paragraphs(rand(1..3))
  }

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

      it "has a game description header" do
        expect(page).to have_content('Game Description')
      end

      it "has a game description" do
        expect(page).to have_selector('#game-data p')
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
          within('#game-data') do
            expect(page).to have_selector(".character_list")
          end
        end

        it "has the Make a move button" do
          expect(page).to have_selector('#respond-to-move')
        end

      end

      describe "with multiple roles" do

        before do
          make_role
          click_link 'Log out'
          login_editor
          make_role
        end

        it 'has a populated list of other characters' do
          within('.character_list') do
            expect(page).to have_selector('li.role')
          end
        end

      end

    end

    describe "with moves" do

      before do
        make_role
        make_a_move
      end

      it 'has a moves section' do
        expect(page).to have_selector('#moves')
      end

      it "has an individual move" do
        expect(page).to have_selector('article.ivanhoe_move')
      end

      it "has the title of a move" do
        expect(page).to have_selector('.ivanhoe_move h1')
      end

      it "has a link to the individual move page" do
        expect(page).to have_selector('.ivanhoe_move h1 a')
      end

      it "has a link to your role within the move block" do
        expect(page).to have_selector(".byline a")
      end

      it 'has a date for the move' do
        within('.ivanhoe_move') do
          expect(page).to have_selector('time')
        end
      end

      it "has the Respond to move button" do
        first('.new_source').click
        expect(page).to have_selector('#respond-to-move')
      end

      it "has the move excerpt" do
        expect(page).to have_selector('.excerpt')
      end

      describe "and with a response to a move" do

        before do
          main_page_respond_to_move
        end

        it 'has a move source block with Source header' do
          expect(page).to have_selector('.game-discussion-source h3')
        end

        it 'has a list of source moves' do
          expect(page).to have_selector('.game-discussion-source ul li')
        end

        it 'has a source move that is linked to an individual move page' do
          expect(page).to have_selector('.game-discussion-source ul a li')
        end

        it 'has a move responses block with Response header' do
          expect(page).to have_selector('.game-discussion-response h3')
        end

        it 'has a list of response moves' do
          expect(page).to have_selector('.game-discussion-response ul li')
        end

        it 'has a response move that is linked to an individual move page' do
          expect(page).to have_selector('.game-discussion-response ul li a')
        end

      end

    end

   # The following pagination tests are no longer relevant due to
   #  infinite scroll. We need to write tests to make sure that infinite
   # scroll works. Can make capybara execute script to scroll to bottom
   # of the page.

    # describe "with over 10 moves" do

    #   before do
    #     make_role
    #     11.times { make_a_move }
    #   end

    #   it 'has pagination' do
    #     expect(page).to have_selector('.pagination')
    #   end

    #   it 'indicates the current page in pagination' do
    #     within('.pagination', match: :first) do
    #       expect(page).to have_selector('.current')
    #     end
    #   end

    # end

  end

end

    # describe 'with other characters' do

    #   #logout
    #   #login with different credentials
    #   #make role
    #   #check for others character section and list within section

    # end

