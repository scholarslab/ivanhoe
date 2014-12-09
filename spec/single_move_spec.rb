require 'spec_helper'

describe 'Single Move View', :type => :feature, :js => true do

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

  describe 'An individual move page' do

    before do
      first('.game-title a').click
      make_role
      rand(2..5).times { make_a_move }
    end

    describe 'with no responses' do

      before do
        first('#moves a').click
      end

      it 'has a move article' do
        expect(page).to have_selector('.single-move')
      end

      it "has a linked move title" do
        within('.single-move') do
          expect(page).to have_selector('h1 a')
        end
      end

      it 'has the linked role name of the author' do
        within('.single-move') do
          expect(page).to have_selector('.byline a')
        end
      end

      it 'has the date of the move' do
        within('.single-move') do
          expect(page).to have_selector('time')
        end
      end

      it 'has the move content' do
        within('.single-move') do
          expect(page).to have_selector('#moves')
        end
      end

      it 'has a return to game link' do
        within('.single-move') do
          expect(page).to have_link('Return to game')
        end
      end

      it 'has a respond button' do
        expect(page).to have_link('Respond')
      end

      it 'has the game description block' do
        expect(page).to have_selector('.game-description')
      end

    end

    describe 'with responses' do

      before do
        first('#moves a').click
        respond_to_move
      end

      describe "the response move" do

        before do
          first('#moves a').click
        end

        it 'has move source section' do
          expect(page).to have_selector('.discussion-source')
        end

        it 'has source label' do
          within('.discussion-source') do
            expect(page).to have_selector('h3')
            expect(page).to have_content('Source')
          end
        end

        it 'has list of source moves' do
          within('.discussion-source') do
            expect(page).to have_selector('ul')
          end
        end

        it 'has a linked move title for the source' do
          within('.discussion-source') do
            expect(page).to have_selector('ul li a')
          end
        end

      end

      describe "the source move" do

        before do
          moves = page.all('#moves article')
          second_move_title = moves[1].find('h1 a', match: :first)
          second_move_title.click
        end

        it 'has move response section' do
          expect(page).to have_selector('.discussion-response')
        end

        it 'has responses label' do
          within('.discussion-response') do
            expect(page).to have_selector('h3')
            expect(page).to have_content('Responses')
          end
        end

        it 'has list of response moves' do
          within('.discussion-response') do
            expect(page).to have_selector('ul')
          end
        end

        it 'has a linked move title for the response' do
          within('.discussion-response') do
            expect(page).to have_selector('ul li a')
          end
        end

      end

      describe 'a move with both source and response' do

        before do
          first('#moves a').click
          respond_to_move
          moves = page.all('#moves article')
          second_move_title = moves[1].find('h1 a', match: :first)
          second_move_title.click
        end

        it 'has move source section' do
          expect(page).to have_selector('.discussion-source')
        end

        it 'has source label' do
          within('.discussion-source') do
            expect(page).to have_selector('h3')
            expect(page).to have_content('Source')
          end
        end

        it 'has list of source moves' do
          within('.discussion-source') do
            expect(page).to have_selector('ul')
          end
        end

        it 'has a linked move title for the source' do
          within('.discussion-source') do
            expect(page).to have_selector('ul li a')
          end
        end

        it 'has move response section' do
          expect(page).to have_selector('.discussion-response')
        end

        it 'has responses label' do
          within('.discussion-response') do
            expect(page).to have_selector('h3')
            expect(page).to have_content('Responses')
          end
        end

        it 'has list of response moves' do
          within('.discussion-response') do
            expect(page).to have_selector('ul')
          end
        end

        it 'has a linked move title for the response' do
          within('.discussion-response') do
            expect(page).to have_selector('ul li a')
          end
        end

      end

    end

  end

end
